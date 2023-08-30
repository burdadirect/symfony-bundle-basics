<?php

namespace HBM\BasicsBundle\Entity\Traits;

use HBM\BasicsBundle\Command\AbstractSerialCommand;

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
        return $this->getVarValueParsed() === AbstractSerialCommand::STATE_IDLE;
    }

    public function isInterrupted(): bool
    {
        return $this->getVarValueParsed() === AbstractSerialCommand::STATE_INTERRUPTED;
    }

    public function isRunning(): bool
    {
        return !$this->isIdle() && !$this->isInterrupted();
    }
}
