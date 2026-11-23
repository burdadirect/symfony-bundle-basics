<?php

namespace HBM\BasicsBundle\Command\Enum;

enum PostExecutionResult
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
