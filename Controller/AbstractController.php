<?php

namespace HBM\BasicsBundle\Controller;

use Doctrine\ORM\EntityRepository;
use HBM\BasicsBundle\Entity\AbstractEntity;
use HBM\BasicsBundle\Entity\Interfaces\NoticeInterface;
use HBM\BasicsBundle\Traits\ServiceDependencies\ParameterBagDependencyTrait;
use HBM\BasicsBundle\Traits\ServiceDependencies\SessionDependencyTrait;
use HBM\BasicsBundle\Util\AttributeMessage\AttributeMessage;
use HBM\BasicsBundle\Util\Result\Result;
use HBM\BasicsBundle\Util\Wording\EntityWording;
use HBM\Leadgen\Traits\ServiceDependencies\FormHelperDependencyTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends BaseController {

  use
    SessionDependencyTrait,
    FormHelperDependencyTrait,
    ParameterBagDependencyTrait;

  /**
   * Render template and set default values.
   *
   * @param string|null $template
   * @param array $data
   * @param Response|null $response
   *
   * @return Response
   */
  abstract protected function renderCustom(?string $template, array $data = [], Response $response = NULL) : Response;

  /****************************************************************************/
  /* OBJECTS                                                                  */
  /****************************************************************************/

  /**
   * @param Request $request
   * @param EntityRepository $repo
   * @param $id
   * @param EntityWording $wording
   * @param AttributeMessage|string|null $attribute
   * @param string|callable $redirect
   *
   * @return object|JsonResponse|RedirectResponse|null
   */
  protected function findObject(Request $request, EntityRepository $repo, $id, EntityWording $wording, $attribute = NULL, $redirect = '/') {
    $wording->setId($id);

    /** @var AbstractEntity $object */
    $object = $id ? $repo->find($id) : NULL;

    if (is_callable($redirect)) {
      $redirect = call_user_func($redirect, $object);
    }

    // Check if object is null.
    if ($return = $this->checkForNull($request, $object, $wording, $redirect)) {
      return $return;
    }

    // Check if object is granted attribute.
    if ($return = $this->checkForAttribute($request, $object, $wording, $attribute, $redirect)) {
      return $return;
    }

    return $object;
  }

  /**
   * @param Request $request
   * @param AbstractEntity|null $object
   * @param EntityWording $wording
   * @param string $redirect
   *
   * @return JsonResponse|RedirectResponse|null
   */
  protected function checkForNull(Request $request, ?AbstractEntity $object, EntityWording $wording, string $redirect) {
    if ($object === NULL) {
      if ($request->isXmlHttpRequest()) {
        return new JsonResponse(['success' => FALSE], Response::HTTP_NOT_FOUND);
      }

      $this->addFlashMessage('error', $wording->labelHtml('', TRUE).' wurde nicht gefunden.');
      return $this->redirect($this->generateOrReturnUrl($redirect)/*, Response::HTTP_NOT_FOUND*/);
    }

    return NULL;
  }

  /**
   * @param Request $request
   * @param AbstractEntity $object
   * @param EntityWording $wording
   * @param AttributeMessage|string|null $attribute
   * @param string $redirect
   *
   * @return JsonResponse|RedirectResponse|null
   */
  protected function checkForAttribute(Request $request, AbstractEntity $object, EntityWording $wording, $attribute, string $redirect) {
    if (!($attribute instanceof AttributeMessage)) {
      $attribute = new AttributeMessage($attribute);
    }

    if ($attribute->getAttribute() && !$this->isGranted($attribute->getAttribute(), $object)) {
      if ($request->isXmlHttpRequest()) {
        return new JsonResponse(['success' => FALSE], Response::HTTP_FORBIDDEN);
      }

      $entityString = $wording->assignName($object)->labelHtml();
      if ($message = $attribute->getMessage()) {
        $this->addFlashMessage($message->getLevel(), $message->formatMessage($entityString));
      } else {
        $this->addFlashMessage('error', 'Sie können die Aktion für '.$entityString.' aufgrund fehlender Rechte nicht durchführen.');
      }

      return $this->redirect($this->generateOrReturnUrl($redirect)/*, Response::HTTP_FORBIDDEN*/);
    }

    return NULL;
  }

  /**
   * @param callable $callable
   * @param array $params
   * @param string|null $messageSuccess
   * @param \Closure|null $responseSuccess
   * @param string|null $messageError
   * @param \Closure|null $responseError
   * @param array $sprintArgs
   *
   * @return Response|null
   */
  protected function tryToPersistEntity(callable $callable, array $params, ?string $messageSuccess, ?\Closure $responseSuccess, ?string $messageError, ?\Closure $responseError = NULL, array $sprintArgs = []) : ?Response {
    try {
      call_user_func($callable, ...$params);

      if ($messageSuccess) {
        $this->addFlashMessage('success', sprintf($messageSuccess, ...$sprintArgs));
      }

      if ($responseSuccess) {
        return $responseSuccess();
      }
    } catch (\Exception $e) {
      if ($messageError) {
        $this->addFlashErrorsForException(sprintf($messageError, ...$sprintArgs), $e);
      }

      if ($responseError) {
        return $responseError();
      }
    }

    return NULL;
  }

  /**
   * @param string $message
   * @param \Exception|null $exception
   */
  abstract protected function addFlashErrorsForException(string $message, \Exception $exception = NULL) : void;

  /****************************************************************************/
  /* MESSAGES                                                                 */
  /****************************************************************************/

  /**
   * @param string $type
   * @param string $message
   */
  protected function addFlashMessage(string $type, string $message) : void {
    $this->session->getFlashBag()->add($type, $message);
  }

  /**
   * @param array|NoticeInterface[] $notices
   * @param string|null $prefix
   * @param string|null $postfix
   */
  protected function addFlashMessagesFromNotices(array $notices, string $prefix = NULL, string $postfix = NULL) : void {
    foreach ($notices as $notice) {
      $string = $notice->getTitle();
      if ($notice->getMessage()) {
        $string = '<div class="normal"><strong>'.$string.'</strong><br />'.$notice->getMessage().'</div>';
      }
      $this->addFlashMessage($notice->getAlertLevel(), $prefix.$string.$postfix);
    }
  }

  /**
   * @param Result $result
   * @param string|null $prefix
   * @param string|null $postfix
   */
  protected function addFlashMessagesFromResult(Result $result, string $prefix = NULL, string $postfix = NULL) : void {
    foreach ($result->getMessages() as $message) {
      $this->addFlashMessage($message->getLevel(), $prefix.$message->getMessage().$postfix);
    }
  }

  /**
   * @param string $type
   * @param string $template
   * @param array $data
   */
  protected function addFlashMessageFromTemplate(string $type, string $template, array $data = []) : void {
    $this->session->getFlashBag()->add($type, $this->renderView($template, $data));
  }

  /**
   * @param Result $result
   * @param string|null $prefix
   * @param string|null $postfix
   */
  protected function addFlashNoticesFromResult(Result $result, string $prefix = NULL, string $postfix = NULL) : void {
    $this->addFlashMessagesFromNotices($result->getNotices(), $prefix, $postfix);
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
   * @param string $textYes
   * @param string $textNo
   * @param string $flashMessage
   *
   * @return null|RedirectResponse|Response|FormInterface
   */
  protected function prepareConfirmAction(Request $request, $urlYes, $urlNo, string $textYes = 'Ja', string $textNo = 'nein', string $flashMessage = 'Aktion abgebrochen') {
    $builder = $this->formHelper->createFormBuilderConfirmation($urlYes, $textYes, $textNo);
    $form = $builder->getForm();
    $form->handleRequest($request);

    try {
      /** @var ClickableInterface $submitAndNo */
      $submitAndNo = $form->get('group_buttons')->get('submit_and_no');
      /** @var ClickableInterface $submitAndYes */
      $submitAndYes = $form->get('group_buttons')->get('submit_and_yes');
    } catch (\OutOfBoundsException $oobe) {
      $this->addFlashMessage('info', 'Antwort wurde nicht erkannt. Aktion abgebrochen!');
      return $this->redirect($urlNo);
    }


    if ($form->isSubmitted() && $submitAndNo->isClicked()) {
      if ($flashMessage) {
        $this->addFlashMessage('info', $flashMessage);
      }
      return $this->redirect($urlNo);
    }

    if ($form->isSubmitted() && $submitAndYes->isClicked()) {
      return NULL;
    }

    return $form;
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
   * @param string $flashMessage
   *
   * @return null|RedirectResponse|Response
   */
  protected function confirmActionHelper(Request $request, $urlYes, $urlNo, $confirmTitle = NULL, $confirmDetails = NULL, string $textYes = 'Ja', string $textNo = 'Nein', string $flashMessage = 'Aktion abgebrochen') {
    $return = $this->prepareConfirmAction($request, $urlYes, $urlNo, $textYes, $textNo, $flashMessage);

    if ($return instanceof FormInterface) {
      return $this->renderCustom($this->pb->get('hbm.basics')['confirm']['template'], [
        'navi' => $this->pb->get('hbm.basics')['confirm']['navi'],
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

  /****************************************************************************/
  /* HELPER                                                                   */
  /****************************************************************************/

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

}
