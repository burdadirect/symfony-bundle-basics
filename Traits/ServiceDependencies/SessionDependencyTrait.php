<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

trait SessionDependencyTrait {

  protected SessionInterface $session;

  /**
   * @required
   *
   * @param SessionInterface $session
   *
   * @return void
   */
  public function setSession(SessionInterface $session): void {
    $this->session = $session;
  }

}
