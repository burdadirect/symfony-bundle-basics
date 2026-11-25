<?php

namespace HBM\BasicsBundle\Command;

use HBM\BasicsBundle\Command\Attributes\Extendable\PostExecute;
use HBM\BasicsBundle\Command\Attributes\Extendable\PreExecute;
use HBM\BasicsBundle\Command\Enum\PreExecutionResult;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractExtendableCommand extends Command
{
    /** @var array<string, PreExecutionResult> */
    private array $preExecutionResults = [];

    private InputInterface $extendedInput;
    private OutputInterface $extendedOutput;

    public function getPreExecutionResults(): array
    {
        return $this->preExecutionResults;
    }

    public function getPreExecutionResult(string $methodName): ?PreExecutionResult
    {
        return $this->preExecutionResults[$methodName] ?? null;
    }

    public function setPreExecutionResult(string $methodName, PreExecutionResult $preExecutionResult): self
    {
        $this->preExecutionResults[$methodName] = $preExecutionResult;

        return $this;
    }

    public function getExtendedInput(): InputInterface
    {
        return $this->extendedInput;
    }

    public function setExtendedInput(InputInterface $extendedInput): void
    {
        $this->extendedInput = $extendedInput;
    }

    public function getExtendedOutput(): OutputInterface
    {
        return $this->extendedOutput;
    }

    public function setExtendedOutput(OutputInterface $extendedOutput): void
    {
        $this->extendedOutput = $extendedOutput;
    }

    /* ABSTRACT FUNCTIONS */

    abstract protected function executeLogic(InputInterface $input, OutputInterface $output): ?int;

    /* EXTENSION FUNCTIONS */

    private function getAttributedMethods(string $class): array
    {
        $methods = [];

        $reflectionCommand = new \ReflectionObject($this);
        foreach ($reflectionCommand->getInterfaces() as $reflectionInterface) {
            foreach ($reflectionInterface->getMethods() as $reflectionMethod) {
                $attributes = $reflectionMethod->getAttributes($class);

                if (count($attributes) > 0) {
                    $methods[$reflectionMethod->getName()] = $attributes[0]->getArguments()[0] ?? 0;
                }
            }
        }

        // Sort pre executing methods in descending order.
        if ($class === PreExecute::class) {
            arsort($methods, SORT_NUMERIC);
        }

        // Sort post executing methods in ascending order.
        if ($class === PostExecute::class) {
            asort($methods, SORT_NUMERIC);
        }

        return array_keys($methods);
    }

    protected function preExecute(self $command, ?int &$exitCode): bool
    {
        // array_any(...) in combination with fn (arrow-function) can not be used here.
        // Reason: The callback receives $exitCode by reference, but the arrow function captures $exitCode by value.
        foreach ($this->getAttributedMethods(PreExecute::class) as $method) {
            $preExecutionResult = $this->{$method}($command, $exitCode);

            $this->setPreExecutionResult($method, $preExecutionResult);
            if ($preExecutionResult->isAborted()) {
                return true;
            }
        }

        return false;
    }

    protected function postExecute(self $command, ?int &$exitCode): bool
    {
        // array_any(...) in combination with fn (arrow-function) can not be used here.
        // Reason: The callback receives $exitCode by reference, but the arrow function captures $exitCode by value.
        foreach ($this->getAttributedMethods(PostExecute::class) as $method) {
            if ($this->{$method}($command, $exitCode)->isAborted()) {
                return true;
            }
        }

        return $exitCode;
    }

    /**
     * @see Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->setExtendedInput($input);
        $this->setExtendedOutput($output);

        $aborted = $this->preExecute($this, $exitCode);

        // Only execute logic if all pre executes run and no exit code has already been set.
        if (!$aborted) {
            $exitCode = $this->executeLogic($this->getExtendedInput(), $this->getExtendedOutput());
        }

        $this->postExecute($this, $exitCode);

        return $exitCode ?? Command::INVALID;
    }
}
