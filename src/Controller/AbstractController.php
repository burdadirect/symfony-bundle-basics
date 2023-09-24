<?php

namespace HBM\BasicsBundle\Controller;

use Doctrine\ORM\EntityRepository;
use HBM\BasicsBundle\Entity\AbstractEntity;
use HBM\BasicsBundle\Entity\Interfaces\NoticeInterface;
use HBM\BasicsBundle\Service\FormHelper;
use HBM\BasicsBundle\Traits\ServiceDependencies\ParameterBagDependencyTrait;
use HBM\BasicsBundle\Traits\ServiceDependencies\RequestStackDependencyTrait;
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
use Symfony\Component\HttpFoundation\Session\SessionInterface;

abstract class AbstractController extends BaseController
{
    use ParameterBagDependencyTrait;
    use RequestStackDependencyTrait;

    /**
     * Render template and set default values.
     */
    abstract protected function renderCustom(?string $template, array $data = [], Response $response = null): Response;

    /**
     * Returns a session interface (probably from the request stack).
     */
    abstract protected function getSession(): SessionInterface;

    abstract protected function getFormHelper(): FormHelper;

    /* OBJECTS */

    /**
     * @param null|AttributeMessage|string $attribute
     * @param callable|string              $redirect
     *
     * @return null|JsonResponse|object|RedirectResponse
     */
    protected function findObject(Request $request, EntityRepository $repo, $id, EntityWording $wording, $attribute = null, $redirect = '/')
    {
        $wording->setId($id);

        /** @var AbstractEntity $object */
        $object = $id ? $repo->find($id) : null;

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
     * @return null|JsonResponse|RedirectResponse
     */
    protected function checkForNull(Request $request, ?AbstractEntity $object, EntityWording $wording, string $redirect)
    {
        if ($object === null) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['success' => false], Response::HTTP_NOT_FOUND);
            }

            $this->addFlashMessage('error', $wording->labelHtml('', true) . ' wurde nicht gefunden.');

            return $this->redirect($this->generateOrReturnUrl($redirect)/* , Response::HTTP_NOT_FOUND */);
        }

        return null;
    }

    /**
     * @param null|AttributeMessage|string $attribute
     *
     * @return null|JsonResponse|RedirectResponse
     */
    protected function checkForAttribute(Request $request, AbstractEntity $object, EntityWording $wording, $attribute, string $redirect)
    {
        if (!($attribute instanceof AttributeMessage)) {
            $attribute = new AttributeMessage($attribute);
        }

        if ($attribute->getAttribute() && !$this->isGranted($attribute->getAttribute(), $object)) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['success' => false], Response::HTTP_FORBIDDEN);
            }

            $entityString = $wording->assignName($object)->labelHtml();

            if ($message = $attribute->getMessage()) {
                $this->addFlashMessage($message->getLevel(), $message->formatMessage($entityString));
            } else {
                $this->addFlashMessage('error', 'Sie können die Aktion für ' . $entityString . ' aufgrund fehlender Rechte nicht durchführen.');
            }

            return $this->redirect($this->generateOrReturnUrl($redirect)/* , Response::HTTP_FORBIDDEN */);
        }

        return null;
    }

    protected function tryToPersistEntity(callable $callable, array $params, ?string $messageSuccess, ?\Closure $responseSuccess, ?string $messageError, \Closure $responseError = null, array $sprintArgs = []): ?Response
    {
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

        return null;
    }

    abstract protected function addFlashErrorsForException(string $message, \Exception $exception = null): void;

    /* MESSAGES */

    protected function addFlashMessage(string $type, string $message): void
    {
        $this->getSession()->getFlashBag()->add($type, $message);
    }

    /**
     * @param array|NoticeInterface[] $notices
     */
    protected function addFlashMessagesFromNotices(array $notices, string $prefix = null, string $postfix = null): void
    {
        foreach ($notices as $notice) {
            $string = $notice->getTitle();

            if ($notice->getMessage()) {
                $string = '<div class="normal"><strong>' . $string . '</strong><br />' . $notice->getMessage() . '</div>';
            }
            $this->addFlashMessage($notice->getAlertLevel(), $prefix . $string . $postfix);
        }
    }

    protected function addFlashMessagesFromResult(Result $result, string $prefix = null, string $postfix = null): void
    {
        foreach ($result->getMessages() as $message) {
            $this->addFlashMessage($message->getLevel(), $prefix . $message->getMessage() . $postfix);
        }
    }

    protected function addFlashMessageFromResult(string $type, string $message, Result $result): void
    {
        $this->addFlashMessage($type, $this->renderView('@HBMBasics/flash-messages/result-messages.html.twig', [
          'message' => $message,
          'result'  => $result,
        ]));
    }

    protected function addFlashMessageFromTemplate(string $type, string $template, array $data = []): void
    {
        $this->getSession()->getFlashBag()->add($type, $this->renderView($template, $data));
    }

    protected function addFlashNoticesFromResult(Result $result, string $prefix = null, string $postfix = null): void
    {
        $this->addFlashMessagesFromNotices($result->getNotices(), $prefix, $postfix);
    }

    /* CONFIRM */

    /**
     * Checks if a request has been confirmed (is post and contains confirmed flag).
     *
     * @return null|FormInterface|RedirectResponse|Response
     */
    protected function prepareConfirmAction(Request $request, $urlYes, $urlNo, string $textYes = 'Ja', string $textNo = 'nein', string $flashMessage = 'Aktion abgebrochen')
    {
        $builder = $this->getFormHelper()->createFormBuilderConfirmation($urlYes, $textYes, $textNo);
        $form    = $builder->getForm();
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
            return null;
        }

        return $form;
    }

    /**
     * Checks if a request has been confirmed (is post and contains confirmed flag).
     *
     * @param null $confirmTitle
     * @param null $confirmDetails
     *
     * @return null|RedirectResponse|Response
     */
    protected function confirmActionHelper(Request $request, $urlYes, $urlNo, $confirmTitle = null, $confirmDetails = null, string $textYes = 'Ja', string $textNo = 'Nein', string $flashMessage = 'Aktion abgebrochen', array $titleParts = [])
    {
        $return = $this->prepareConfirmAction($request, $urlYes, $urlNo, $textYes, $textNo, $flashMessage);

        if ($return instanceof FormInterface) {
            return $this->renderCustom($this->pb->get('hbm.basics')['confirm']['template'], [
              'navi'     => $this->pb->get('hbm.basics')['confirm']['navi'],
              'formView' => $return->createView(),
              'title'    => $confirmTitle,
              'details'  => $confirmDetails,
              'titleParts' => $titleParts,
            ]);
        }

        return $return;
    }

    /**
     * Checks if a request has been confirmed (is post and contains confirmed flag).
     *
     * @param null $confirmTitle
     * @param null $confirmDetails
     *
     * @return null|RedirectResponse|Response
     */
    protected function confirmDeleteActionHelper(Request $request, $urlYes, $urlNo, $confirmTitle = null, $confirmDetails = null, array $titleParts = [])
    {
        return $this->confirmActionHelper($request, $urlYes, $urlNo, $confirmTitle, $confirmDetails, 'Ja, löschen', 'Nein, doch nicht löschen', titleParts: $titleParts);
    }

    /* HELPER */

    protected function generateOrReturnUrl(string $url, array $params = []): string
    {
        $first = $url[0] ?? '';

        if ($first !== '/') {
            return $this->generateUrl($url, $params);
        }

        return $url;
    }
}
