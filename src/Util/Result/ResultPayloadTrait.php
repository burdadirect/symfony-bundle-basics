<?php

namespace HBM\BasicsBundle\Util\Result;

trait ResultPayloadTrait
{

    protected array $payloads = [];

    public function setPayloads(array $payloads): self
    {
        $this->payloads = $payloads;

        return $this;
    }

    public function getPayloads(): array
    {
        return $this->payloads;
    }

    public function getPayload(string $key, mixed $default = null): mixed
    {
        return $this->payloads[$key] ?? $default;
    }

    public function setPayload(string $key, mixed $payload): self
    {
        $this->payloads[$key] = $payload;

        return $this;
    }

}
