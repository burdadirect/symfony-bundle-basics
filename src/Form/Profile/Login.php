<?php

namespace HBM\BasicsBundle\Form\Profile;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Login extends AbstractType
{
    private bool|array $togglePassword = false;

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['togglePassword']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->togglePassword = $options['togglePassword'] ?? false;

        $builder
          ->add($this->getSubFormDefault($builder));
    }

    protected function getSubFormDefault(FormBuilderInterface $builder): FormBuilderInterface
    {
        $group_default = $builder->create('group_default', FormType::class, [
          'inherit_data' => true,
        ]);

        $attrPassword = [];

        if (false !== $this->togglePassword) {
            $attrPassword['data-toggable-password'] = is_array($this->togglePassword) ? json_encode($this->togglePassword) : true;
        }

        $group_default
          ->add('email', EmailType::class, [
            'label' => 'E-Mail-Adresse',
          ])
          ->add('password', PasswordType::class, [
            'label' => 'Passwort',
            'attr'  => $attrPassword,
          ])
          ->add('rememberMe', CheckboxType::class, [
            'label'    => 'Eingeloggt bleiben?',
            'required' => false,
          ]);

        return $group_default;
    }

    public function getBlockPrefix(): string
    {
        return 'hbm_basics_form_profile_login';
    }
}
