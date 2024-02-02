<?php

namespace HBM\BasicsBundle\Command\Attributes\Extendable;

/**
 * Methods marked with the PreExecute attribute will be executed in descending order of priority.
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class PreExecute
{
    public function __construct(public int $priority = 0)
    {
    }
}
