<?php

namespace HBM\BasicsBundle\Command\Interfaces;

use HBM\BasicsBundle\Command\AbstractExtendableCommand;
use HBM\BasicsBundle\Command\Attributes\Extendable\PostExecute;
use HBM\BasicsBundle\Command\Attributes\Extendable\PreExecute;
use HBM\BasicsBundle\Command\Enum\PostExecutionResult;
use HBM\BasicsBundle\Command\Enum\PreExecutionResult;

interface ExecutionLoggerCommandInterface
{
    #[PreExecute(100)]
    public function beginExecutionLogger(AbstractExtendableCommand $command, ?int &$exitCode): PreExecutionResult;

    #[PostExecute(100)]
    public function commitExecutionLogger(AbstractExtendableCommand $command, ?int &$exitCode): PostExecutionResult;
}
