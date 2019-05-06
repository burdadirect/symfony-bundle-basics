<?php

namespace HBM\BasicsBundle\Controller;

use HBM\BasicsBundle\Entity\Interfaces\Addressable;
use HBM\BasicsBundle\Service\AbstractDoctrineHelper;
use HBM\BasicsBundle\Service\AbstractServiceHelper;
use HBM\BasicsBundle\Util\Result\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends BaseController {

  /**
   * @var AbstractServiceHelper
   */
  protected $sh;

  /**
   * @var AbstractDoctrineHelper
   */
  protected $dh;

  /**
   * Render template and set default values.
   *
   * @param string $template
   * @param array $data
   * @param Response $response
   *
   * @return Response
   */
  abstract protected function renderCustom($template, array $data = [], Response $response = NULL) : Response;

  /****************************************************************************/
  /* MESSAGES                                                                 */
  /****************************************************************************/

  /**
   * @param string $type
   * @param string $message
   */
  protected function addFlashMessage(string $type, string $message) : void {
    if ($session = $this->sh->session()) {
      $session->getFlashBag()->add($type, $message);
    }
  }

  /**
   * @param Result $result
   * @param null|string $prefix
   * @param null|string $postfix
   */
  protected function addFlashMessagesFromResult(Result $result, $prefix = NULL, $postfix = NULL) : void {
    foreach ($result->getMessages() as $message) {
      $this->addFlashMessage($message->getLevel(), $prefix.$message->getMessage().$postfix);
    }
  }

  /**
   * @param Result $result
   * @param null|string $prefix
   * @param null|string $postfix
   */
  protected function addFlashNoticesFromResult(Result $result, $prefix = NULL, $postfix = NULL) : void {
    foreach ($result->getNotices() as $notice) {
      $string = $notice->getTitle();
      if ($notice->getMessage()) {
        $string = '<div class="normal"><strong>'.$string.'</strong><br />'.$notice->getMessage().'</div>';
      }
      $this->addFlashMessage($notice->getAlertLevel(), $prefix.$string.$postfix);
    }
  }

  /****************************************************************************/
  /* FORM                                                                     */
  /****************************************************************************/

  /**
   * Add a submit button to a form.
   *
   * @param FormInterface $form
   * @param string $label
   * @param string $class
   * @param string $name
   */
  public function addSubmitButton(FormInterface $form, $label = 'Speichern', $name = 'submit', $class = 'btn btn-lg btn-block btn-primary') : void {
    try {
      if (!$form->has('group_buttons')) {
        $form->add('group_buttons', FormType::class, [
          'attr' => [
            'class' => 'hbm-form-buttons'
          ],
          'inherit_data' => TRUE,
          'label' => FALSE,
        ]);
      }

      $form->get('group_buttons')->add($name, SubmitType::class, [
        'label' => $label,
        'attr' => ['class' => $class]
      ]);
    } catch (\Exception $e) {
    }
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
   * Creates a form to confirm/decline an action
   *
   * @param $url
   * @param $buttonTextYes
   * @param $buttonTextNo
   *
   * @return FormInterface
   */
  protected function createGenericConfirmForm($url, $buttonTextYes, $buttonTextNo) : FormInterface {
    $form = $this->createFormBuilder([], ['translation_domain' => FALSE])
      ->setAction($url)
      ->setMethod('POST')
      ->getForm();

    $this->addSubmitButton($form, $buttonTextYes, 'submit_and_yes', 'btn btn-lg btn-block btn-success');
    $this->addSubmitButton($form, $buttonTextNo, 'submit_and_no', 'btn btn-lg btn-block btn-danger');

    return $form;
  }

  /**
   * Creates a form to delete an entity by id.
   *
   * @param $id integer The entity id
   * @param $route string The name of the route
   * @return FormInterface
   */
  protected function createGenericDeleteForm($id, $route) : FormInterface {
    $form = $this->createFormBuilder([], [
      'action' => $this->generateUrl($route, ['id' => $id]),
      'method' => 'DELETE',
      'translation_domain' => FALSE
    ])->getForm();

    $this->addSubmitButton($form, 'Löschen');

    return $form;
  }

  /**
   * Creates a form to create an entity.
   *
   * @param $form_type
   * @param array $data
   * @param $route
   * @param array $options
   * @param string $button
   * @param string $button_class
   *
   * @return FormInterface
   */
  protected function createGenericForm($form_type, $data, $route = null, array $options = [], $button = 'Abschicken', $button_class= 'btn btn-lg btn-block btn-primary') : FormInterface {
    $form = $this->createForm($form_type, $data, array_merge($options, [
      'action' => $this->generateOrReturnUrl($route),
      'method' => 'POST',
      'translation_domain' => FALSE,
    ]));

    $this->addSubmitButton($form, $button, $button_class);

    return $form;
  }

  /**
   * Creates a form to create an entity.
   *
   * @param $form_type
   * @param Addressable|null $entity
   * @param $route
   * @param array $options
   * @param string $button
   * @param string $button_class
   *
   * @return FormInterface
   */
  protected function createGenericCreateForm($form_type, Addressable $entity = null, $route = null, array $options = [], $button = 'Erzeugen', $button_class= 'btn btn-lg btn-block btn-primary') : FormInterface {
    $form = $this->createForm($form_type, $entity, array_merge($options, [
      'action' => $this->generateOrReturnUrl($route),
      'method' => 'POST',
      'translation_domain' => FALSE,
    ]));

    $this->addSubmitButton($form, $button, $button_class);

    return $form;
  }

  /**
   * Creates a form to edit an entity.
   *
   * @param $form_type
   * @param Addressable|null $entity
   * @param $route
   * @param array $options
   * @param string $button
   * @param string $button_class
   *
   * @return FormInterface
   */
  protected function createGenericEditForm($form_type, Addressable $entity = null, $route = null, array $options = [], $button = 'Speichern', $button_class= 'btn btn-lg btn-block btn-primary') : FormInterface {
    $form = $this->createForm($form_type, $entity, array_merge($options, [
      'action' => $this->generateOrReturnUrl($route, $entity ? ['id' => $entity->getId()] : []),
      'method' => 'PUT',
      'translation_domain' => FALSE,
    ]));

    $this->addSubmitButton($form, $button, $button_class);

    return $form;
  }

  /**
   * @param string $url
   * @param array $params
   *
   * @return string
   */
  protected function generateOrReturnUrl(string $url, array $params = []) : string {
    $first = $url[0] ?? '';
    if ('/' !== $first) {
      return $this->generateUrl($url, $params);
    }

    return $url;
  }

  /****************************************************************************/
  /* CONFIRM                                                                  */
  /****************************************************************************/

  /**
   * Checks if a request has been confirmed (is post and contains confirmed flag).
   *
   * @param Request $request
   * @param $urlYes
   * @param $urlNo
   * @param null $confirmTitle
   * @param null $confirmDetails
   * @param string $textYes
   * @param string $textNo
   *
   * @return null|RedirectResponse|Response|FormInterface
   */
  protected function prepareConfirmAction(Request $request, $urlYes, $urlNo, $confirmTitle = NULL, $confirmDetails = NULL, $textYes = 'Ja', $textNo = 'nein') {
    $formInterface = $this->createGenericConfirmForm($urlYes, $textYes, $textNo);
    $formInterface->handleRequest($request);

    try {
      /** @var ClickableInterface $submitAndNo */
      $submitAndNo = $formInterface->get('group_buttons')->get('submit_and_no');
      /** @var ClickableInterface $submitAndYes */
      $submitAndYes = $formInterface->get('group_buttons')->get('submit_and_yes');
    } catch (\OutOfBoundsException $oobe) {
      $this->addFlashMessage('info', 'Antwort wurde nicht erkannt. Aktion abgebrochen!');
      return $this->redirect($urlNo);
    }


    if ($formInterface->isSubmitted() && $submitAndNo->isClicked()) {
      $this->addFlashMessage('info', 'Aktion abgebrochen!');
      return $this->redirect($urlNo);
    }

    if ($formInterface->isSubmitted() && $submitAndYes->isClicked()) {
      return NULL;
    }

    return $formInterface;
  }

  /**
   * Checks if a request has been confirmed (is post and contains confirmed flag).
   *
   * @param Request $request
   * @param $urlYes
   * @param $urlNo
   * @param null $confirmTitle
   * @param null $confirmDetails
   * @param string $textYes
   * @param string $textNo
   *
   * @return null|RedirectResponse|Response
   */
  protected function confirmActionHelper(Request $request, $urlYes, $urlNo, $confirmTitle = NULL, $confirmDetails = NULL, $textYes = 'Ja', $textNo = 'nein') {
    $return = $this->prepareConfirmAction($request, $urlYes, $urlNo, $textYes, $textNo);

    if ($return instanceof FormInterface) {
      return $this->renderCustom($this->sh->parameterBag()->get('hbm_basics.confirm.template'), [
        'navi' => $this->sh->parameterBag()->get('hbm_basics.confirm.navi'),
        'formView' => $return->createView(),
        'title' => $confirmTitle,
        'details' => $confirmDetails,
      ]);
    }

    return $return;
  }

  /**
   * Checks if a request has been confirmed (is post and contains confirmed flag).
   *
   * @param Request $request
   * @param $urlYes
   * @param $urlNo
   * @param null $confirmTitle
   * @param null $confirmDetails
   *
   * @return null|RedirectResponse|Response
   */
  protected function confirmDeleteActionHelper(Request $request, $urlYes, $urlNo, $confirmTitle = NULL, $confirmDetails = NULL) {
    return $this->confirmActionHelper($request, $urlYes, $urlNo, $confirmTitle, $confirmDetails, 'Ja, löschen', 'Nein, doch nicht löschen');
  }

}
