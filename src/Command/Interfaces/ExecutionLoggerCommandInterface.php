<?php

namespace HBM\BasicsBundle\Command\Interfaces;

use HBM\BasicsBundle\Command\AbstractExtendableCommand;
use HBM\BasicsBundle\Command\Attributes\Extendable\PostExecute;
use HBM\BasicsBundle\Command\Attributes\Extendable\PreExecute;

interface ExecutionLoggerCommandInterface
{
    #[PreExecute(100)]
    public function beginExecutionLogger(AbstractExtendableCommand $command, ?int &$exitCode): bool;

    #[PostExecute(100)]
    public function commitExecutionLogger(AbstractExtendableCommand $command, ?int &$exitCode): bool;
}
