<?php

namespace HBM\Basics\Security\Listeners;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class AjaxAuthenticationListener {

  /**
   * Handles security related exceptions.
   *
   * See: https://gist.github.com/xanf/1015146
   *
   * @param GetResponseForExceptionEvent $event An GetResponseForExceptionEvent instance
   */
  public function onCoreException(GetResponseForExceptionEvent $event) {
    $exception = $event->getException();
    $request = $event->getRequest();

    if ($request->isXmlHttpRequest()) {
      if ($exception instanceof AuthenticationException) {
        $response = new JsonResponse();
        $response->setData(['success' => FALSE, 'reason' => 'User is not authenticated!']);
        $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
        $event->setResponse($response);
      } elseif ($exception instanceof AccessDeniedException) {
        $response = new JsonResponse();
        $response->setData(['success' => FALSE, 'reason' => 'User is not authorized!']);
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
      }
    }
  }

}
