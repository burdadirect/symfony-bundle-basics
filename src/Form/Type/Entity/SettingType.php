<?php

namespace HBM\BasicsBundle\Form\Type\Entity;

use HBM\BasicsBundle\Entity\Interfaces\SettingInterface;
use HBM\BasicsBundle\Util\Data\SettingVarType;
use HBM\BasicsBundle\Util\Data\State;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SettingType extends AbstractType
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

        /** @var SettingInterface $setting */
        $setting = $builder->getData();

        $group
          ->add('varNature', TextType::class, [
            'label'    => 'Art',
            'required' => true,
            // 'help' => 'Um welche Art von Einstellung handelt es sich? Gängige Werte sind: state, flag, misc'
          ])
          ->add('varType', ChoiceType::class, [
            'label'    => 'Typ',
            'required' => true,
            'choices'  => array_flip(SettingVarType::flatten()),
            'expanded' => false,
            'multiple' => false,
          ])
          ->add('varKey', TextType::class, [
            'label'    => 'Schlüssel',
            'required' => true,
          ]);

        $this->addVarValue($group, $setting);

        $group
          ->add('notice', TextareaType::class, [
            'label'    => 'Notiz',
            'required' => false,
            'attr'     => ['rows' => '5'],
          ])
          ->add('editable', ChoiceType::class, [
            'label'    => 'Bearbeitbar?',
            'required' => true,
            'choices'  => [
              'nein' => State::PENDING,
              'ja'   => State::ACTIVE,
            ],
            'expanded' => true,
            'multiple' => false,
          ])
          ->add('previewable', ChoiceType::class, [
            'label'    => 'Vorschau?',
            'required' => true,
            'choices'  => [
              'nein' => State::PENDING,
              'ja'   => State::ACTIVE,
            ],
            'expanded' => true,
            'multiple' => false,
          ]);

        return $group;
    }

    protected function addVarValue(FormBuilderInterface $group, SettingInterface $setting): void {
        if ($setting->getVarType() === SettingVarType::JSON) {
            $editorMode     = 'ace/mode/json';
            $editorTextarea = false;
        } elseif ($setting->getVarType() === SettingVarType::HTML) {
            $editorMode     = 'ace/mode/html';
            $editorTextarea = false;
        } else {
            $editorMode     = 'ace/mode/text';
            $editorTextarea = true;
        }

        $group->add('varValue', TextareaType::class, [
          'label'    => 'Wert',
          'required' => false,
          'attr'     => [
            'rows'             => '5',
            'data-ace-id'      => 'editor-twig-settings',
            'data-ace-options' => json_encode(['ace' => ['mode' => $editorMode], 'general' => ['textarea' => $editorTextarea]]),
          ],
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'hbm_form_type_setting';
    }
}
