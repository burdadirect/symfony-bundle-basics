<?php


namespace HBM\BasicsBundle\Form\Type;

use HBM\BasicsBundle\Form\DataTransformer\StringToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class LinesToArrayTextAreaType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->addModelTransformer(new StringToArrayTransformer("\n"));
  }

  public function getParent() {
    return TextareaType::class;
  }

}
