<?php

namespace HBM\BasicsBundle\Form\Profile;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordReset extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
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
        'constraints' => [
          new NotBlank(),
        ],
      ]);

    return $group_default;
  }

  public function getBlockPrefix(): string {
    return 'hbm_basics_form_profile_password_reset';
  }

}
