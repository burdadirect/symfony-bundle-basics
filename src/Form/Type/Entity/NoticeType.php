<?php

namespace HBM\BasicsBundle\Form\Type\Entity;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class NoticeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
          ->add($this->getSubFormDefault($builder));
    }

    protected function getSubFormDefault(FormBuilderInterface $builder): FormBuilderInterface
    {
        $group = $builder->create('group_default', FormType::class, [
          'inherit_data' => true,
          'card'         => true,
          'label'        => 'Allgemein',
        ]);

        $group
          ->add('title', TextType::class, [
            'label'    => 'Titel',
            'required' => true,
          ])
          ->add('message', TextareaType::class, [
            'label'    => 'Notiz',
            'required' => false,
          ]);

        return $group;
    }

    public function getBlockPrefix(): string
    {
        return 'hbm_form_type_notice';
    }
}
