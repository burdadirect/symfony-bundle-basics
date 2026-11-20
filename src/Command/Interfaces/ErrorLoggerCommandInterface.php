<?php

namespace HBM\BasicsBundle\Command\Interfaces;

use HBM\BasicsBundle\Command\AbstractExtendableCommand;
use HBM\BasicsBundle\Command\Attributes\Extendable\PreExecute;
use HBM\BasicsBundle\Command\Enum\PreExecutionResult;

interface ErrorLoggerCommandInterface
{
    #[PreExecute(100)]
    public function prepareErrorLogger(AbstractExtendableCommand $command, ?int &$exitCode): PreExecutionResult;

    public function logError(string $error, array $context = []): void;
}
