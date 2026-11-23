<?php

namespace HBM\BasicsBundle\Command\Enum;

enum PreExecutionResult
{
    case SUCCEEDED;
    case SKIPPED;
    case ABORTED;
    case FAILED;

    public function isAborted(): bool
    {
        return $this === self::ABORTED;
    }
}
