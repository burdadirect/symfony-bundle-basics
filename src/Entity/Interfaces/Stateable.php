<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

interface Stateable extends Addressable
{
    /**
     * Set state.
     *
     * @return self
     */
    public function setState(int $state);

    /**
     * Get state.
     */
    public function getState(): int;

    /* CUSTOM */

    public function isActive(): bool;

    public function isPending(): bool;

    public function isReview(): bool;

    public function isBlocked(): bool;
}
