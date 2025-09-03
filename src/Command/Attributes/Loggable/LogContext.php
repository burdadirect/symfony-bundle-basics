<?php

namespace HBM\BasicsBundle\Command\Attributes\Loggable;

/**
 * Define methods or properties as log context.
 * If return value is scalar:
 *      Will be added to the context under the given key.
 * If return value is array:
 *      Will be added to the context under the given key or merged if no key is given.
 * If return value is object:
 *      The supplied method is used to get the value from the object.
 *      The result value will be treated like the two first cases.
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_CLASS_CONSTANT)]
class LogContext
{
    public function __construct(public ?string $key = null, public ?string $method = null)
    {
    }
}
