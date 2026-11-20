<?php

namespace HBM\BasicsBundle\Command\Interfaces;

use HBM\BasicsBundle\Command\AbstractExtendableCommand;
use HBM\BasicsBundle\Command\Attributes\Extendable\PostExecute;
use HBM\BasicsBundle\Command\Attributes\Extendable\PreExecute;
use HBM\BasicsBundle\Command\Enum\PostExecutionResult;
use HBM\BasicsBundle\Command\Enum\PreExecutionResult;

interface JournaledCommandInterface
{
    #[PreExecute(200)]
    public function startJournaledConsoleOutput(AbstractExtendableCommand $command, ?int &$exitCode): PreExecutionResult;

    #[PostExecute(200)]
    public function stopJournaledConsoleOutput(AbstractExtendableCommand $command, int &$exitCode): PostExecutionResult;
}
