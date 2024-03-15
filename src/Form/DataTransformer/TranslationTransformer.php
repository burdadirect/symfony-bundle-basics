<?php

namespace HBM\BasicsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslationTransformer implements DataTransformerInterface
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function transform($value): string
    {
        if (is_callable($value)) {
            $value = $value();
        }

        if (is_array($value)) {
            return $this->translator->trans(...$value);
        }

        return $this->translator->trans($value);
    }

    public function reverseTransform($value): mixed
    {
        throw new TransformationFailedException('Reversing of translation not possible.');
    }
}
