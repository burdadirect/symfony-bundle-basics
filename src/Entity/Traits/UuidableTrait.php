<?php

namespace HBM\BasicsBundle\Entity\Traits;

trait UuidableTrait
{
    protected ?string $uuid = null;

    /**
     * Set GUID (only alphanumeric, due to restrictions of third party apis).
     */
    public function setUuid(): static
    {
        if ($this->uuid === null) {
            $this->uuid = $this->generateUuid();
        }

        return $this;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    protected function generateUuid(): string
    {
        try {
            $uuid = uniqid('', true) . random_int(1000, 9999);
        } catch (\Exception) {
            $uuid = uniqid('', true) . '5000';
        }

        return preg_replace('/[^A-Za-z0-9]/', '', $uuid);
    }
}
