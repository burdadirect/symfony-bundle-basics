<?php

namespace HBM\BasicsBundle\Util\Result\Traits;

trait ResultPayloadTrait
{
    /** @var array<string, mixed> */
    protected array $payloads = [];

    public function setPayloads(array $payloads): static
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

    public function setPayload(string $key, mixed $payload): static
    {
        $this->payloads[$key] = $payload;

        return $this;
    }
}
