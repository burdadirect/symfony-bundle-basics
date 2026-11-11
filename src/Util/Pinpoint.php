<?php

namespace HBM\BasicsBundle\Util;

class Pinpoint
{
    public function __construct(
        private ?string $class = null,
        private ?string $method = null,
        private ?int $line = null,
        private ?string $custom = null
    ) {
    }

    public function locate(string $format = '%2$s[%3$s]'): string
    {
        return $this->custom ?? sprintf($format, $this->class, $this->method, $this->line);
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(?string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(?string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getLine(): ?int
    {
        return $this->line;
    }

    public function setLine(?int $line): self
    {
        $this->line = $line;

        return $this;
    }

    public function getCustom(): ?string
    {
        return $this->custom;
    }

    public function setCustom(?string $custom): self
    {
        $this->custom = $custom;

        return $this;
    }

}
