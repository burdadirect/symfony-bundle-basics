<?php

namespace HBM\BasicsBundle\Service;

use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Twig\Environment;

abstract class AbstractServiceHelper {

  /**
   * @var ContainerInterface
   */
  protected $container;

  /**
   * @var ParameterBagInterface
   */
  private $parameterBag;

  /**
   * ServiceHelper constructor.
   *
   * @param ContainerInterface $container
   * @param ParameterBagInterface $parameterBag
   */
  public function __construct(ContainerInterface $container, ParameterBagInterface $parameterBag) {
    $this->container = $container;
    $this->parameterBag = $parameterBag;
  }

  /**
   * @return FormHelper|object
   */
  public function formHelper() {
    return $this->container->get(FormHelper::class);
  }

  /****************************************************************************/
  /* SYMFONY                                                                  */
  /****************************************************************************/

  /**
   * @return ParameterBagInterface
   */
  public function parameterBag() : ParameterBagInterface {
    return $this->parameterBag;
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
   * @return EventDispatcher|object;
   */
  public function eventDispatcher() {
    return $this->container->get('event_dispatcher');
  }

  /**
 * @return RouterInterface|object
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
   * @return Environment|object
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
