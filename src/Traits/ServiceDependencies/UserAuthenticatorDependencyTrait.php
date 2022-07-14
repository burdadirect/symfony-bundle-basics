<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

trait UserAuthenticatorDependencyTrait {

  protected UserAuthenticatorInterface $userAuthenticator;

  /**
   * @required
   *
   * @param UserAuthenticatorInterface $userAuthenticator
   *
   * @return void
   */
  public function setUserAuthenticator(UserAuthenticatorInterface $userAuthenticator): void {
    $this->userAuthenticator = $userAuthenticator;
  }

}
