<?php

namespace HBM\BasicsBundle\Form\Type;

use HBM\BasicsBundle\Form\DataTransformer\StringToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CsvToArrayTextType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder->addModelTransformer(new StringToArrayTransformer(',', ', '));
  }

  public function getParent(): string {
    return TextType::class;
  }

}
