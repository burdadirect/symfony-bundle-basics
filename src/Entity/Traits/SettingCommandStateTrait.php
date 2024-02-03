<?php

namespace HBM\BasicsBundle\Entity\Traits;

use HBM\BasicsBundle\Command\Interfaces\SerialCommandInterface;

/**
 * @method null|string getVarNature()
 * @method mixed       getVarValueParsed()
 */
trait SettingCommandStateTrait
{
    public function isCommandState(): bool
    {
        return $this->getVarNature() === 'state';
    }

    public function isIdle(): bool
    {
        return $this->getVarValueParsed() === SerialCommandInterface::STATE_IDLE;
    }

    public function isPaused(): bool
    {
        return $this->getVarValueParsed() === SerialCommandInterface::STATE_PAUSED;
    }

    public function isInterrupted(): bool
    {
        return $this->getVarValueParsed() === SerialCommandInterface::STATE_INTERRUPTED;
    }

    public function isRunning(): bool
    {
        return !$this->isIdle() && !$this->isPaused() && !$this->isInterrupted();
    }
}
