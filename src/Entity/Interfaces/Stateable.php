<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

interface Stateable extends Addressable
{
    public function setState(int $state): self;

    public function getState(): int;

    /* CUSTOM */

    public function isActive(): bool;

    public function isPending(): bool;

    public function isReview(): bool;

    public function isBlocked(): bool;
}
