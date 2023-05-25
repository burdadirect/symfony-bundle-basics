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

class FormHelper {

  private array $config;

  private FormFactoryInterface $formFactoryInterface;

  private RouterInterface $routerInterface;

  /**
   * FormHelper constructor.
   * @param FormFactoryInterface $formFactoryInterface
   * @param RouterInterface $routerInterface
   * @param array $config
   */
  public function __construct(FormFactoryInterface $formFactoryInterface, RouterInterface $routerInterface, array $config) {
    $this->formFactoryInterface = $formFactoryInterface;
    $this->routerInterface = $routerInterface;
    $this->config = $config;
  }

  /****************************************************************************/

  /**
   * Create form.
   *
   * @param string $type
   * @param $data
   * @param array $options
   *
   * @return FormInterface
   */
  private function createForm(string $type, $data = null, array $options = []) : FormInterface {
    return $this->formFactoryInterface->create($type, $data, $options);
  }

  /**
   * Create form builder.
   *
   * @param $data
   * @param array $options
   *
   * @return FormBuilderInterface
   */
  private function createFormBuilder($data = null, array $options = []) : FormBuilderInterface {
    return $this->formFactoryInterface->createBuilder(FormType::class, $data, $options);
  }

  /**
   * Prepare form builder.
   *
   * @param $route
   * @param $data
   * @param array $options
   *
   * @return FormBuilderInterface
   */
  public function initFormBuilder($route, $data = null, array $options = []) : FormBuilderInterface {
    $optionsDefault = [
      'method' => 'POST',
      'translation_domain' => FALSE
    ];
    if ($route) {
      $optionsDefault['action'] = $this->generateOrReturnUrl($route);
    }

    return $this->createFormBuilder($data, array_merge($optionsDefault, $options));
  }

  /**
   * @param string $url
   * @param array $params
   * @param int $referenceType
   *
   * @return string
   */
  public function generateOrReturnUrl(string $url, array $params = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH) : string {
    $first = $url[0] ?? '';
    if ('/' !== $first) {
      return $this->routerInterface->generate($url, $params, $referenceType);
    }

    return $url;
  }

  /****************************************************************************/

  /**
   * Creates a form to confirm/decline an action
   *
   * @param $url
   * @param $buttonTextYes
   * @param $buttonTextNo
   *
   * @return FormBuilderInterface
   */
  public function createFormBuilderConfirmation($url, $buttonTextYes, $buttonTextNo) : FormBuilderInterface {
    $formBuilder = $this->initFormBuilder($url);

    $this->addSubmitButton($formBuilder, $buttonTextYes, 'submit_and_yes', $this->getButtonClasses('affirm'));
    $this->addSubmitButton($formBuilder, $buttonTextNo, 'submit_and_no', $this->getButtonClasses('decline'));

    return $formBuilder;
  }

  /**
   * Creates a form to delete an entity by id.
   *
   * @param $id integer The entity id
   * @param $route string The name of the route
   *
   * @return FormBuilderInterface
   */
  public function createFormBuilderDeletion($id, $route) : FormBuilderInterface {
    $formBuilder = $this->initFormBuilder(NULL, NULL, [
      'action' => $this->generateOrReturnUrl($route, ['id' => $id]),
      'method' => 'DELETE',
    ]);

    $this->addSubmitButton($formBuilder, 'Löschen');

    return $formBuilder;
  }

  /**
   * Creates a form to create an entity.
   *
   * @param string $formType
   * @param $data
   * @param string|null $route
   * @param array $options
   * @param string|null $button
   * @param string|null $buttonClass
   *
   * @return FormInterface
   */
  public function createFormType(string $formType, $data, string $route = null, array $options = [], ?string $button = 'Abschicken', ?string $buttonClass = null) : FormInterface {
    $form = $this->createForm($formType, $data, array_merge($options, [
      'action' => $this->generateOrReturnUrl($route),
      'method' => 'POST',
      'translation_domain' => FALSE,
    ]));

    if ($button) {
      $this->addSubmitButton($form, $button, 'submit', $buttonClass);
    }

    return $form;
  }

  /**
   * Creates a form to create an entity.
   *
   * @param string $formType
   * @param Addressable|null $entity
   * @param string|null $route
   * @param array $options
   * @param string|null $button
   * @param string|null $buttonClass
   *
   * @return FormInterface
   */
  public function createFormTypeCreation(string $formType, Addressable $entity = null, string $route = null, array $options = [], ?string $button = 'Erzeugen', ?string $buttonClass = null) : FormInterface {
    $form = $this->createForm($formType, $entity, array_merge($options, [
      'action' => $this->generateOrReturnUrl($route),
      'method' => 'POST',
      'translation_domain' => FALSE,
    ]));

    if ($button) {
      $this->addSubmitButton($form, $button, 'submit', $buttonClass);
    }

    return $form;
  }

  /**
   * Creates a form to edit an entity.
   *
   * @param string $formType
   * @param Addressable|null $entity
   * @param string|null $route
   * @param array $options
   * @param string|null $button
   * @param string|null $buttonClass
   *
   * @return FormInterface
   */
  public function createFormTypeEditing(string $formType, Addressable $entity = null, string $route = null, array $options = [], ?string $button = 'Speichern', ?string $buttonClass = null) : FormInterface {
    $form = $this->createForm($formType, $entity, array_merge($options, [
      'action' => $this->generateOrReturnUrl($route, $entity ? ['id' => $entity->getId()] : []),
      'method' => 'PUT',
      'translation_domain' => FALSE,
    ]));

    if ($button) {
      $this->addSubmitButton($form, $button, 'submit', $buttonClass);
    }

    return $form;
  }

  /****************************************************************************/
  /* FormBuilderInterface                                                     */
  /****************************************************************************/

  /**
   * Add a form group.
   *
   * @param FormBuilderInterface $builder
   * @param array $options
   * @param string $name
   *
   * @return FormBuilderInterface
   */
  public function addGroup(FormBuilderInterface $builder, array $options = [], string $name = 'group_generic') : FormBuilderInterface  {
    $group = $builder->create($name, FormType::class, array_merge([
      'inherit_data' => true,
      'card' => true,
    ], $options));

    $builder->add($group);

    return $group;
  }

  /****************************************************************************/
  /* FormInterface                                                            */
  /****************************************************************************/

  /**
   * Add a submit button to a form.
   *
   * @param FormInterface|FormBuilderInterface $form
   * @param string $label
   * @param string $name
   * @param string|null $class
   * @param array $optionsButton
   * @param array $optionsGroup
   */
  public function addSubmitButton($form, $label = 'Speichern', $name = 'submit', ?string $class = NULL, array $optionsButton = [], array $optionsGroup = []) : void {
    if (!$form->has('group_buttons')) {
      $form->add('group_buttons', FormType::class, array_merge([
        'attr' => [
          'class' => 'hbm-form-buttons'
        ],
        'inherit_data' => TRUE,
        'label' => FALSE,
      ], $optionsGroup));
    }

    $form->get('group_buttons')->add($name, SubmitType::class, array_merge([
      'label' => $label,
      'label_html' => TRUE,
      'attr' => ['class' => $class ?: $this->getButtonClasses('primary')],
    ], $optionsButton));
  }

  /**
   * Get a submit button from a form.
   *
   * @param FormInterface $form
   * @param string $name
   *
   * @return FormInterface|SubmitButton
   */
  public function getSubmitButton(FormInterface $form, string $name) {
    /** @var SubmitButton $button */
    $button = NULL;
    if ($form->has('group_buttons') && $form->get('group_buttons')->has($name)) {
      try {
        $button = $form->get('group_buttons')->get($name);
      } catch (\OutOfBoundsException $oobe) {
      }
    }
    return $button;
  }

  /**
   * @param FormInterface $formInterface
   * @param string $buttonName
   *
   * @return bool
   */
  public function isSubmitButtonClicked(FormInterface $formInterface, string $buttonName) : bool {
    $button = $this->getSubmitButton($formInterface, $buttonName);

    return $button && $button->isClicked();
  }

  /**
   * @param string $mode
   *
   * @return string
   */
  public function getButtonClasses(string $mode) : string {
    return ($this->config['submit_button_classes']['default'] ?? NULL).' '.($this->config['submit_button_classes'][$mode] ?? NULL);
  }

}
