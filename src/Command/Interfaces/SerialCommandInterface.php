<?php

namespace HBM\BasicsBundle\Command\Interfaces;

use HBM\BasicsBundle\Command\AbstractExtendableCommand;
use HBM\BasicsBundle\Command\Attributes\Extendable\PostExecute;
use HBM\BasicsBundle\Command\Attributes\Extendable\PreExecute;

interface SerialCommandInterface
{
    public const string STATE_IDLE        = 'idle';
    public const string STATE_PAUSED      = 'paused';
    public const string STATE_INTERRUPTED = 'interrupted';

    #[PreExecute(500)]
    public function markAsBusy(AbstractExtendableCommand $command, ?int &$exitCode): bool;

    #[PostExecute(500)]
    public function markAsIdle(AbstractExtendableCommand $command, int &$exitCode): bool;
}
