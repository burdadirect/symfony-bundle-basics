<?php

namespace HBM\BasicsBundle\Service;

use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

abstract class AbstractServiceHelper {

  /**
   * @var ContainerInterface
   */
  protected $container;

  /**
   * ServiceHelper constructor.
   *
   * @param ContainerInterface $container
   */
  public function __construct(ContainerInterface $container) {
    $this->container = $container;
  }

  /****************************************************************************/
  /* SYMFONY                                                                  */
  /****************************************************************************/

  /**
   * @return ParameterBagInterface|object
   */
  public function parameterBag() {
    return $this->container->get('parameter_bag');
  }

  /**
   * @return TokenStorage|object
   */
  public function securityTokenStorage() {
    return $this->container->get('security.token_storage');
  }

  /**
   * @return AuthorizationChecker|object
   */
  public function securityAuthorizationChecker() {
    return $this->container->get('security.authorization_checker');
  }

  /**
   * @return EncoderFactory|object
   */
  public function securityEncoderFactory() {
    return $this->container->get('public.security.encoder_factory');
  }

  /**
   * @return EventDispatcher|object;
   */
  public function eventDispatcher() {
    return $this->container->get('event_dispatcher');
  }

  /**
 * @return Router|object
 */
  public function router() {
    return $this->container->get('router');
  }

  /**
   * Returns the FormFactory Service (used for TypeHinting)
   *
   * @return FormFactory|object
   */
  public function formFactory() {
    return $this->container->get('form.factory');
  }

  /**
   * Returns the Session (used for TypeHinting)
   *
   * @return Session|object
   */
  public function session() {
    return $this->container->get('session');
  }

  /**
   * @return EngineInterface|object
   */
  public function templating() {
    return $this->container->get('templating');
  }

  /**
   * @return \Twig_Environment|object
   */
  public function twig() {
    return $this->container->get('twig');
  }

  /**
   * @param string $logger
   * @return Logger|object
   */
  public function logger($logger = 'logger') {
    return $this->container->get($logger);
  }

}
