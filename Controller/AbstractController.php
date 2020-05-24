<?php

namespace HBM\BasicsBundle\Controller;

use Doctrine\ORM\EntityRepository;
use HBM\BasicsBundle\Entity\AbstractEntity;
use HBM\BasicsBundle\Service\AbstractDoctrineHelper;
use HBM\BasicsBundle\Service\AbstractServiceHelper;
use HBM\BasicsBundle\Util\AttributeMessage\AttributeMessage;
use HBM\BasicsBundle\Util\Result\Result;
use HBM\BasicsBundle\Util\Wording\EntityWording;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
  /* OBJECTS                                                                  */
  /****************************************************************************/

  /**
   * @param Request $request
   * @param EntityRepository $repo
   * @param $id
   * @param EntityWording $wording
   * @param AttributeMessage|string|null $attribute
   * @param string $redirect
   *
   * @return object|JsonResponse|RedirectResponse|null
   */
  protected function findObject(Request $request, EntityRepository $repo, $id, EntityWording $wording, $attribute = NULL, string $redirect = '/') {
    $wording->setId($id);

    /** @var AbstractEntity $object */
    $object = $id ? $repo->find($id) : NULL;
    if ($object === NULL) {
      if ($request->isXmlHttpRequest()) {
        return new JsonResponse(['success' => FALSE], Response::HTTP_NOT_FOUND);
      }

      $this->addFlashMessage('error', $wording->labelHtml('', TRUE).' wurde nicht gefunden.');
      return $this->redirect($this->generateOrReturnUrl($redirect)/*, Response::HTTP_NOT_FOUND*/);
    }

    if (!($attribute instanceof AttributeMessage)) {
      $attribute = new AttributeMessage($attribute);
    }

    if ($attribute->getAttribute() && !$this->isGranted($attribute->getAttribute(), $object)) {
      $entityString = $wording->assignName($object)->labelHtml();
      if ($message = $attribute->getMessage()) {
        $this->addFlashMessage($message->getLevel(), $message->formatMessage($entityString));
      } else {
        $this->addFlashMessage('error', 'Sie können die Aktion für '.$entityString.' aufgrund fehlender Rechte nicht durchführen.');
      }
      return $this->redirect($this->generateOrReturnUrl($redirect)/*, Response::HTTP_FORBIDDEN*/);
    }

    return $object;
  }

  /**
   * @param callable $callable
   * @param array $params
   * @param string $messageSuccess
   * @param \Closure $redirectSuccess
   * @param string $messageError
   * @param \Closure $redirectError
   * @param array $sprintArgs
   *
   * @return RedirectResponse|null
   */
  protected function tryToPersistEntity(callable $callable, array $params, string $messageSuccess, \Closure $redirectSuccess, string $messageError, \Closure $redirectError = NULL, array $sprintArgs = []) : ?RedirectResponse {
    try {
      call_user_func($callable, ...$params);

      $this->addFlashMessage('success', sprintf($messageSuccess, ...$sprintArgs));

      if ($redirectSuccess) {
        return $redirectSuccess();
      }
    } catch (\Exception $e) {
      $this->addFlashErrorsForException(sprintf($messageError, ...$sprintArgs), $e);

      if ($redirectError) {
        return $redirectError();
      }
    }

    return NULL;
  }

  /**
   * @param AbstractEntity $entity
   * @param \Closure|null $closure
   */
  protected function persistAndFlushEntity(AbstractEntity $entity, \Closure $closure = NULL) : void {
    $this->dh->getOM()->persist($entity);
    $this->dh->getOM()->flush();

    if ($closure) {
      $closure();
    }
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
    if ($session = $this->sh->session()) {
      $session->getFlashBag()->add($type, $message);
    }
  }

  /**
   * @param Result $result
   * @param null|string $prefix
   * @param null|string $postfix
   */
  protected function addFlashMessagesFromNotices(array $notices, $prefix = NULL, $postfix = NULL) : void {
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
   * @param null|string $prefix
   * @param null|string $postfix
   */
  protected function addFlashMessagesFromResult(Result $result, $prefix = NULL, $postfix = NULL) : void {
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
    if ($session = $this->sh->session()) {
      $session->getFlashBag()->add($type, $this->renderView($template, $data));
    }
  }

  /**
   * @param Result $result
   * @param null|string $prefix
   * @param null|string $postfix
   */
  protected function addFlashNoticesFromResult(Result $result, $prefix = NULL, $postfix = NULL) : void {
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
  protected function prepareConfirmAction(Request $request, $urlYes, $urlNo, $textYes = 'Ja', $textNo = 'nein', $flashMessage = 'Aktion abgebrochen') {
    $builder = $this->sh->formHelper()->createFormBuilderConfirmation($urlYes, $textYes, $textNo);
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
   *
   * @return null|RedirectResponse|Response
   */
  protected function confirmActionHelper(Request $request, $urlYes, $urlNo, $confirmTitle = NULL, $confirmDetails = NULL, $textYes = 'Ja', $textNo = 'Nein', $flashMessage = 'Aktion abgebrochen') {
    $return = $this->prepareConfirmAction($request, $urlYes, $urlNo, $textYes, $textNo, $flashMessage);

    if ($return instanceof FormInterface) {
      return $this->renderCustom($this->sh->parameterBag()->get('hbm.basics')['confirm']['template'], [
        'navi' => $this->sh->parameterBag()->get('hbm.basics')['confirm']['navi'],
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
