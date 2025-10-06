<?php

namespace HBM\BasicsBundle\Util;

use HBM\BasicsBundle\Entity\AbstractEntity;

class LogObject
{
    public function __construct(
        private ?string $message = null,
        private array $contexts = [],
        private array $entities = []
    ) {
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): LogObject
    {
        $this->message = $message;

        return $this;
    }

    public function getContexts(): array
    {
        return $this->contexts;
    }

    public function setContexts(array $contexts): LogObject
    {
        $this->contexts = $contexts;

        return $this;
    }

    public function addContext(string $key, mixed $value): LogObject
    {
        $this->contexts[$key] = $value;

        return $this;
    }

    public function addContexts(array $contexts): LogObject
    {
        $this->contexts = array_merge($this->contexts, $contexts);

        return $this;
    }

    public function getEntities(): array
    {
        return $this->entities;
    }

    public function setEntities(array $entities): LogObject
    {
        $this->entities = $entities;

        return $this;
    }

    public function addEntity(AbstractEntity $entity): LogObject
    {
        $this->entities[] = $entity;

        return $this;
    }

    public function pinpoint(string $methodOrFunction, string|int|null $line = null): LogObject
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

        if (count($this->entities) > 0) {
            $context['entities'] = array_map(static function (AbstractEntity $entity) {
                return $entity->getIdFQCN();
            }, $this->getEntities());
        }

        return [strip_tags($this->getMessage()), $context];
    }

    public static function arguments(?string $message = null, array $contexts = [], array $entities = []): array
    {
        return (new LogObject($message, $contexts, $entities))->toLoggerArgumentsArray();
    }

    public function copy(): LogObject
    {
        return clone $this;
    }
}
