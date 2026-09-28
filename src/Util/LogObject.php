<?php

namespace HBM\BasicsBundle\Util;

use HBM\BasicsBundle\Entity\AbstractEntity;
use HBM\BasicsBundle\Util\Result\Interfaces\ResultMessagesInterface;
use HBM\BasicsBundle\Util\Result\Result;

class LogObject
{
    public function __construct(
        private ?string $message = null,
        private array $contexts = [],
        private array $entities = [],
        private ?ResultMessagesInterface $result = null
    ) {
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getContexts(): array
    {
        return $this->contexts;
    }

    public function setContexts(array $contexts): self
    {
        $this->contexts = $contexts;

        return $this;
    }

    public function addContext(string $key, mixed $value): self
    {
        $this->contexts[$key] = $value;

        return $this;
    }

    public function addContexts(array $contexts): self
    {
        $this->contexts = array_merge($this->contexts, $contexts);

        return $this;
    }

    public function getEntities(): array
    {
        return $this->entities;
    }

    public function setEntities(array $entities): self
    {
        $this->entities = $entities;

        return $this;
    }

    public function addEntity(AbstractEntity $entity): self
    {
        $this->entities[] = $entity;

        return $this;
    }

    public function getResult(): ?ResultMessagesInterface
    {
        return $this->result;
    }

    public function setResult(?Result $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function pinpoint(string $methodOrFunction, string|int|null $line = null): self
    {
        return $this->addContext('pinpoint', $methodOrFunction . ($line !== null ? '[' . $line . ']' : ''));
    }

    public function toLoggerArgumentsArray(): array
    {
        $context = [];
        foreach ($this->getContexts() as $contextKey => $contextValue) {
            if (!is_string($contextValue)) {
                $context[$contextKey] = json_encode($contextValue);
            } else {
                $context[$contextKey] = $contextValue;
            }
        }

        if (count($this->getEntities()) > 0) {
            $context['entities'] = array_map(static function (AbstractEntity $entity) {
                return $entity->getIdFQCN();
            }, $this->getEntities());
        }

        if ($this->getResult() !== null) {
            foreach ($this->getResult()->getMessages() as $message) {
                $context[] = $message;
            }
        }

        $message = $this->getMessage() ? strip_tags($this->getMessage()) : null;

        return [$message, $context];
    }

    public static function arguments(?string $message = null, array $contexts = [], array $entities = [], ?Result $result = null): array
    {
        return new LogObject($message, $contexts, $entities, $result)->toLoggerArgumentsArray();
    }

    public function copy(): self
    {
        return clone $this;
    }
}
