<?php

namespace HBM\BasicsBundle\Command\Traits;

use HBM\BasicsBundle\Command\AbstractExtendableCommand;
use HBM\BasicsBundle\Command\Attributes\Loggable\LogContext;
use HBM\BasicsBundle\Util\LogObject;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait ErrorLoggerCommandTrait
{
    protected ?array $loggerErrors = null;

    protected ?OutputInterface $loggerOutput = null;

    /* ABSTRACT FUNCTIONS */

    abstract protected function getLogger(): LoggerInterface;

    /* INTERFACE IMPLEMENTATIONS */

    public function prepareErrorLogger(AbstractExtendableCommand $command, ?int &$exitCode): bool
    {
        $this->loggerOutput = $command->getExtendedOutput();

        return true;
    }

    public function logError(string $error, array $context = []): void
    {
        $contextGathered = $this->addContextFromAttributedMethodsOrProperties($context);

        $this->getLogger()->critical(...LogObject::arguments($error, $contextGathered));
        $this->loggerOutput?->writeln($error);

        $this->retainError($error);
    }

    /* HELPER: Messages */

    protected function hasErrors(): ?bool
    {
        if (is_array($this->loggerErrors)) {
            return count($this->loggerErrors) > 0;
        }

        return null;
    }

    protected function resetErrors(): void
    {
        if (is_array($this->loggerErrors)) {
            $this->loggerErrors = [];
        }
    }

    protected function retainError(string $error): void
    {
        if (is_array($this->loggerErrors)) {
            $this->loggerErrors[] = $error;
        }
    }

    /* HELPER: Context */

    protected function addContextFromAttributedMethodsOrProperties(array $context = []): array
    {
        $reflectionClass = new \ReflectionObject($this);
        foreach ($reflectionClass->getMethods() as $reflectionMethod) {
            $attributes = $reflectionMethod->getAttributes(LogContext::class);

            if (count($attributes) > 0) {
                $context = $this->addContextFromAttributedMethodOrProperty($this->{$reflectionMethod->getName()}(), $attributes[0], $context);
            }
        }

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $attributes = $reflectionProperty->getAttributes(LogContext::class);

            if (count($attributes) > 0) {
                $context = $this->addContextFromAttributedMethodOrProperty($this->{$reflectionProperty->getName()}, $attributes[0], $context);
            }
        }

        return $context;
    }

    private function addContextFromAttributedMethodOrProperty(mixed $value, \ReflectionAttribute $attribute, array $context): array
    {
        $key    = $attribute->getArguments()[0] ?? null;
        $method = $attribute->getArguments()[1] ?? null;

        if (is_object($value) && is_string($method)) {
            $value = $value->{$method}();
        }

        if (is_string($key)) {
            $context[$key] = $value;
        } elseif (is_array($value)) {
            $context = array_merge($context, $value);
        } else {
            $context[] = $value;
        }

        return $context;
    }
}
