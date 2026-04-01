<?php

namespace HBM\BasicsBundle\Service;

use HBM\BasicsBundle\Entity\Interfaces\Addressable;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class FormHelper
{
    private array $config;

    private FormFactoryInterface $formFactoryInterface;

    private RouterInterface $routerInterface;

    /**
     * FormHelper constructor.
     */
    public function __construct(FormFactoryInterface $formFactoryInterface, RouterInterface $routerInterface, array $config)
    {
        $this->formFactoryInterface = $formFactoryInterface;
        $this->routerInterface      = $routerInterface;
        $this->config               = $config;
    }

    protected function getTranslationDomain(?string $formType = null): string|false {
      return false;
    }

    /**
     * Create form.
     */
    private function createForm(string $type, mixed $data = null, array $options = []): FormInterface
    {
        return $this->formFactoryInterface->create($type, $data, $options);
    }

    /**
     * Create form builder.
     */
    private function createFormBuilder(mixed $data = null, array $options = []): FormBuilderInterface
    {
        return $this->formFactoryInterface->createBuilder(FormType::class, $data, $options);
    }

    /**
     * Prepare form builder.
     */
    public function initFormBuilder(?string $route, mixed $data = null, array $options = []): FormBuilderInterface
    {
        $optionsDefault = [
          'method'             => 'POST',
          'translation_domain' => $this->getTranslationDomain(),
        ];

        if ($route) {
            $optionsDefault['action'] = $this->generateOrReturnUrl($route);
        }

        return $this->createFormBuilder($data, array_merge($optionsDefault, $options));
    }

    public function generateOrReturnUrl(string $url, array $params = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        $first = $url[0] ?? '';

        if ($first !== '/') {
            return $this->routerInterface->generate($url, $params, $referenceType);
        }

        return $url;
    }

    /**
     * Creates a form to confirm/decline an action
     */
    public function createFormBuilderConfirmation(?string $url, ?string $buttonTextYes, ?string $buttonTextNo, array $options = []): FormBuilderInterface
    {
        $formBuilder = $this->initFormBuilder($url, null, $options);

        $this->addSubmitButton($formBuilder, $buttonTextYes, 'submit_and_yes', $this->getButtonClasses('affirm'));
        $this->addSubmitButton($formBuilder, $buttonTextNo, 'submit_and_no', $this->getButtonClasses('decline'));

        return $formBuilder;
    }

    /**
     * Creates a form to delete an entity by id.
     */
    public function createFormBuilderDeletion(string|int|null $id, ?string $route, array $options = []): FormBuilderInterface
    {
        $formBuilder = $this->initFormBuilder(null, null, array_merge([
          'action' => $this->generateOrReturnUrl($route, ['id' => $id]),
          'method' => 'DELETE',
        ], $options));

        $this->addSubmitButton($formBuilder, 'Löschen');

        return $formBuilder;
    }

    /**
     * Creates a form to create an entity.
     */
    public function createFormType(string $formType, mixed $data, string $route = null, array $options = [], ?string $button = 'Abschicken', string $buttonClass = null): FormInterface
    {
        $form = $this->createForm($formType, $data, array_merge([
          'action'             => $this->generateOrReturnUrl($route),
          'method'             => 'POST',
          'translation_domain' => $this->getTranslationDomain($formType),
        ], $options));

        if ($button) {
            $this->addSubmitButton($form, $button, 'submit', $buttonClass);
        }

        return $form;
    }

    /**
     * Creates a form to create an entity.
     */
    public function createFormTypeCreation(string $formType, Addressable $entity = null, string $route = null, array $options = [], ?string $button = 'Erzeugen', string $buttonClass = null): FormInterface
    {
        $form = $this->createForm($formType, $entity, array_merge([
          'action'             => $this->generateOrReturnUrl($route),
          'method'             => 'POST',
          'translation_domain' => $this->getTranslationDomain($formType),
        ], $options));

        if ($button) {
            $this->addSubmitButton($form, $button, 'submit', $buttonClass);
        }

        return $form;
    }

    /**
     * Creates a form to edit an entity.
     */
    public function createFormTypeEditing(string $formType, Addressable $entity = null, string $route = null, array $options = [], ?string $button = 'Speichern', string $buttonClass = null): FormInterface
    {
        $form = $this->createForm($formType, $entity, array_merge([
          'action'             => $this->generateOrReturnUrl($route, $entity ? ['id' => $entity->getId()] : []),
          'method'             => 'PUT',
          'translation_domain' => $this->getTranslationDomain($formType),
        ], $options));

        if ($button) {
            $this->addSubmitButton($form, $button, 'submit', $buttonClass);
        }

        return $form;
    }

    /* FormBuilderInterface */

    /**
     * Add a form group.
     */
    public function addGroup(FormBuilderInterface $builder, array $options = [], string $name = 'group_generic'): FormBuilderInterface
    {
        $group = $builder->create($name, FormType::class, array_merge([
          'inherit_data' => true,
          'card'         => true,
        ], $options));

        $builder->add($group);

        return $group;
    }

    /* FormInterface */

    public function addSubmitButton(FormInterface|FormBuilderInterface $form, ?string $label = 'Speichern', ?string $name = 'submit', string $class = null, array $optionsButton = [], array $optionsGroup = []): void
    {
        if (!$form->has('group_buttons')) {
            $form->add('group_buttons', FormType::class, array_merge([
              'attr' => [
                'class' => 'hbm-form-buttons',
              ],
              'inherit_data' => true,
              'label'        => false,
            ], $optionsGroup));
        }

        $form->get('group_buttons')->add($name, SubmitType::class, array_merge([
          'label'      => $label,
          'label_html' => true,
          'attr'       => ['class' => $class ?: $this->getButtonClasses('primary')],
        ], $optionsButton));
    }

    /**
     * Get a submit button from a form.
     *
     * @return FormInterface|SubmitButton
     */
    public function getSubmitButton(FormInterface $form, string $name)
    {
        /** @var SubmitButton $button */
        $button = null;

        if ($form->has('group_buttons') && $form->get('group_buttons')->has($name)) {
            try {
                $button = $form->get('group_buttons')->get($name);
            } catch (\OutOfBoundsException) {
            }
        }

        return $button;
    }

    public function isSubmitButtonClicked(FormInterface $formInterface, string $buttonName): bool
    {
        $button = $this->getSubmitButton($formInterface, $buttonName);

        return $button && $button->isClicked();
    }

    public function getButtonClasses(string $mode): string
    {
        return ($this->config['submit_button_classes']['default'] ?? null) . ' ' . ($this->config['submit_button_classes'][$mode] ?? null);
    }
}
