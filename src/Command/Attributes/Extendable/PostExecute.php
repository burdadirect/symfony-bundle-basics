<?php

namespace HBM\BasicsBundle\Command\Attributes\Extendable;

/**
 * Methods marked with the PreExecute attribute will be executed in ascending order of priority.
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class PostExecute
{
    public function __construct(public int $priority = 0)
    {
    }
}
