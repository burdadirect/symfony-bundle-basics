<?php

namespace HBM\BasicsBundle\Controller;

use HBM\BasicsBundle\Service\AbstractDoctrineHelper;
use HBM\BasicsBundle\Service\AbstractServiceHelper;
use HBM\BasicsBundle\Util\Result\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\FormInterface;
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
   * @param null $confirmTitle
   * @param null $confirmDetails
   * @param string $textYes
   * @param string $textNo
   *
   * @return null|RedirectResponse|Response|FormInterface
   */
  protected function prepareConfirmAction(Request $request, $urlYes, $urlNo, $confirmTitle = NULL, $confirmDetails = NULL, $textYes = 'Ja', $textNo = 'nein') {
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
      $this->addFlashMessage('info', 'Aktion abgebrochen!');
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
  protected function confirmActionHelper(Request $request, $urlYes, $urlNo, $confirmTitle = NULL, $confirmDetails = NULL, $textYes = 'Ja', $textNo = 'Nein') {
    $return = $this->prepareConfirmAction($request, $urlYes, $urlNo, $textYes, $textNo);

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
