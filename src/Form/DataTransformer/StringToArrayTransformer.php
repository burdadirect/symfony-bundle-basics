<?php

namespace HBM\BasicsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class StringToArrayTransformer implements DataTransformerInterface
{
    /** @var string */
    protected $separator;

    /** @var string */
    protected $glue;

    /**
     * StringToArrayTransformer constructor.
     */
    public function __construct(string $separator, string $glue = null)
    {
        $this->separator = $separator;
        $this->glue      = $glue ?: $separator;
    }

    /**
     * @return mixed|string
     */
    public function transform($value)
    {
        return implode($this->glue, $value);
    }

    /**
     * @return array|mixed
     */
    public function reverseTransform($value)
    {
        return array_map('trim', explode($this->separator, $value));
    }
}
