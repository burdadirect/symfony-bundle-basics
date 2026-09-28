<?php

namespace HBM\BasicsBundle\Entity\Traits;

use HBM\BasicsBundle\Util\Data\State;

/**
 * @property int $state
 */
trait StateableTrait
{
    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getState(): int
    {
        return $this->state;
    }

    /* CUSTOM */

    public function isActive(): bool
    {
        return $this->getState() === State::ACTIVE;
    }

    public function isPending(): bool
    {
        return $this->getState() === State::PENDING;
    }

    public function isReview(): bool
    {
        return $this->getState() === State::REVIEW;
    }

    public function isBlocked(): bool
    {
        return $this->getState() === State::BLOCKED;
    }
}
