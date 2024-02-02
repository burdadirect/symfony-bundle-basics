<?php

namespace HBM\BasicsBundle\Command\Interfaces;

use HBM\BasicsBundle\Command\AbstractExtendableCommand;
use HBM\BasicsBundle\Command\Attributes\Extendable\PostExecute;
use HBM\BasicsBundle\Command\Attributes\Extendable\PreExecute;

interface JournaledCommandInterface
{
    #[PreExecute(200)]
    public function startJournaledConsoleOutput(AbstractExtendableCommand $command, ?int &$exitCode): bool;

    #[PostExecute(200)]
    public function stopJournaledConsoleOutput(AbstractExtendableCommand $command, int &$exitCode): bool;
}
