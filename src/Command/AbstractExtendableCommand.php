<?php

namespace HBM\BasicsBundle\Command;

use HBM\BasicsBundle\Command\Attributes\Extendable\PostExecute;
use HBM\BasicsBundle\Command\Attributes\Extendable\PreExecute;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractExtendableCommand extends Command
{
    private InputInterface $extendedInput;
    private OutputInterface $extendedOutput;

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

        // Sort pre executing methods in decending order.
        if ($class === PreExecute::class) {
            arsort($methods, SORT_NUMERIC);
        }

        // Sort post executing methods in ascending order.
        if ($class === PostExecute::class) {
            asort($methods, SORT_NUMERIC);
        }

        return array_keys($methods);
    }

    protected function preExecute(AbstractExtendableCommand $command, ?int $exitCode): ?int
    {
        foreach ($this->getAttributedMethods(PreExecute::class) as $method) {
            if (false === $this->{$method}($command, $exitCode)) {
                return $exitCode;
            }
        }

        return $exitCode;
    }

    protected function postExecute(AbstractExtendableCommand $command, int $exitCode): int
    {
        foreach ($this->getAttributedMethods(PostExecute::class) as $method) {
            if (false === $this->{$method}($command, $exitCode)) {
                return $exitCode;
            }
        }

        return $exitCode;
    }

    /**
     * @see \Symfony\Component\Console\Command\Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->setExtendedInput($input);
        $this->setExtendedOutput($output);

        $exitCode = $this->preExecute($this, null);

        if ($exitCode !== null) {
            return $exitCode;
        }

        $exitCode = $this->executeLogic($this->getExtendedInput(), $this->getExtendedOutput());

        return $this->postExecute($this, $exitCode);
    }

}
