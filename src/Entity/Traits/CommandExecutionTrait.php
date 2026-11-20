<?php

namespace HBM\BasicsBundle\Entity\Traits;

use Ramsey\Uuid\Uuid;

trait CommandExecutionTrait
{
    private string $executionId;
    private string $commandId;
    private array $contexts;
    private ?int $exitCode = null;
    private ?\DateTime $exited = null;

    protected function init(string $commandId, array $contexts = [], ?string $executionId = null, ?int $exitCode = null): void
    {
        $this->executionId = $executionId ?? Uuid::uuid4()->toString();
        $this->commandId   = $commandId;
        $this->contexts    = $contexts;
        $this->exitCode    = $exitCode;
    }

    public function getExecutionId(): string
    {
        return $this->executionId;
    }

    public function setExecutionId(string $executionId): self
    {
        $this->executionId = $executionId;

        return $this;
    }

    public function getCommandId(): string
    {
        return $this->commandId;
    }

    public function setCommandId(string $commandId): self
    {
        $this->commandId = $commandId;

        return $this;
    }

    public function getContexts(): array
    {
        return $this->contexts;
    }

    public function setContexts(array $contexts): self
    {
        $this->contexts = [];
        foreach ($contexts as $context) {
            $this->addContext($context);
        }

        return $this;
    }

    public function addContext(mixed $context): self
    {
        if (is_object($context) && method_exists($context, 'getIdFQCN')) {
            $this->contexts[] = $context->getIdFQCN();
        } else {
            $this->contexts[] = $context;
        }

        return $this;
    }

    public function getExitCode(): ?int
    {
        return $this->exitCode;
    }

    public function setExitCode(int $exitCode): self
    {
        $this->exitCode = $exitCode;

        return $this;
    }

    public function getExited(): ?\DateTime
    {
        return $this->exited;
    }

    public function setExited(?\DateTime $exited): self
    {
        $this->exited = $exited;

        return $this;
    }
}
