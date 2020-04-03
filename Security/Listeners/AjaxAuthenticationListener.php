<?php

namespace HBM\BasicsBundle\Security\Listeners;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AjaxAuthenticationListener {

  /**
   * Handles security related exceptions.
   *
   * See: https://gist.github.com/xanf/1015146
   * 
   * @param ExceptionEvent $event
   */
  public function onCoreException(ExceptionEvent $event) {
    $throwable = $event->getThrowable();
    $request = $event->getRequest();

    if ($request->isXmlHttpRequest()) {
      if ($throwable instanceof AuthenticationException) {
        $response = new JsonResponse();
        $response->setData(['success' => FALSE, 'reason' => 'User is not authenticated!']);
        $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
        $event->setResponse($response);
      } elseif ($throwable instanceof AccessDeniedException) {
        $response = new JsonResponse();
        $response->setData(['success' => FALSE, 'reason' => 'User is not authorized!']);
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
      }
    }
  }

}
