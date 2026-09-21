<?php

namespace HBM\BasicsBundle\Form\Type;

use HBM\BasicsBundle\Form\DataTransformer\JsonTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class JsonTextAreaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new JsonTransformer());
    }

    public function getParent(): string
    {
        return TextareaType::class;
    }
}
