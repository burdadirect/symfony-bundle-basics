<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

interface CommandExecutionInterface extends Addressable
{
    public function __construct(string $commandId, array $contexts = [], ?string $executionId = null, ?int $exitCode = null);

    public function getExecutionId(): string;
    public function setExecutionId(string $executionId): self;

    public function getCommandId(): string;
    public function setCommandId(string $commandId): self;

    public function getContexts(): array;
    public function setContexts(array $contexts): self;
    public function addContext(mixed $context): self;

    public function getExitCode(): int|bool;
    public function setExitCode(int|bool $exitCode): self;

    public function getExited(): ?\DateTime;
    public function setExited(?\DateTime $exited): self;
}
