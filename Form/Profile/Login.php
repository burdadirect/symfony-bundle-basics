<?php

namespace HBM\BasicsBundle\Form\Profile;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

class Login extends AbstractType {

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
      ])
      ->add('password', PasswordType::class, [
        'label' => 'Passwort',
      ])
      ->add('rememberMe', CheckboxType::class, [
        'label' => 'Eingeloggt bleiben?',
        'required' => FALSE,
      ]);

    return $group_default;
  }

  public function getBlockPrefix() {
    return 'hbm_basics_form_profile_login';
  }
}
