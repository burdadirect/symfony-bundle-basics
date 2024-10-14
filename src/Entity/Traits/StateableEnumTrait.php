<?php

namespace HBM\BasicsBundle\Entity\Traits;

use HBM\BasicsBundle\Util\Enum\Interfaces\EnumStateInterface;

/**
 * @template TT
 */
trait StateableEnumTrait
{
    /**
     * Set state.
     *
     * @param TT $state
     */
    public function setState(EnumStateInterface $state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state.
     *
     * @return TT
     */
    public function getState(): EnumStateInterface
    {
        return $this->state;
    }
}
