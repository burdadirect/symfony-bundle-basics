<?php

namespace HBM\BasicsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentType extends AbstractType {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
      'disabled' => true,
      'required' => false,
      'mapped' => false,
      'compound' => false,
    ]);
  }

  public function getBlockPrefix(): string {
    return 'hbm_content';
  }

}
