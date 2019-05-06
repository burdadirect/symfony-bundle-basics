<?php

namespace HBM\BasicsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;

class PasswordReset extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add($this->getSubFormDefault($builder));
  }

  protected function getSubFormDefault(FormBuilderInterface $builder) {
    $group_default = $builder->create('group_default', FormType::class, [
      'inherit_data' => true,
    ]);

    $group_default
      ->add('email', EmailType::class, [
        'label' => 'E-Mail-Adresse',
      ]);

    return $group_default;
  }

  public function getBlockPrefix() {
    return 'hbm_basics_password_reset';
  }

}
