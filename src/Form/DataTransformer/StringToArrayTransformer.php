<?php

namespace HBM\BasicsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class StringToArrayTransformer implements DataTransformerInterface
{
    protected string $separator;
    protected string $glue;
    protected bool $noEmpty;

    public function __construct(string $separator, ?string $glue = null, bool $noEmpty = false)
    {
        $this->separator = $separator;
        $this->glue      = $glue ?: $separator;
        $this->noEmpty   = $noEmpty;
    }

    public function transform(mixed $value): string
    {
        return implode($this->glue, $value);
    }

    public function reverseTransform(mixed $value): array
    {
        $data = array_map('trim', explode($this->separator, $value));
        if ($this->noEmpty) {
          $data = array_diff($data, ['', null]);
        }
        return $data;
    }
}
