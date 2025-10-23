<?php

namespace HBM\BasicsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class StringToArrayTransformer implements DataTransformerInterface
{
    protected string $separator;
    protected string $glue;

    public function __construct(string $separator, ?string $glue = null)
    {
        $this->separator = $separator;
        $this->glue      = $glue ?: $separator;
    }

    public function transform(mixed $value)
    {
        return implode($this->glue, $value);
    }

    public function reverseTransform(mixed $value)
    {
        return array_map('trim', explode($this->separator, $value));
    }
}
