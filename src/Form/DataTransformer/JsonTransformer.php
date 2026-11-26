<?php

namespace HBM\BasicsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class JsonTransformer implements DataTransformerInterface
{
    public function __construct(protected int $flags = 0)
    {
    }

    /**
     * @throws \JsonException
     */
    public function transform(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return json_encode($value, JSON_THROW_ON_ERROR | $this->flags);
    }

    /**
     * @throws \JsonException
     */
    public function reverseTransform(mixed $value): ?array
    {
        if ($value === null) {
            return null;
        }

        return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
    }
}
