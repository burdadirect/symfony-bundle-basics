<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

trait AuthenticationUtilsDependencyTrait {

  protected AuthenticationUtils $authenticationUtils;

  /**
   * @required
   *
   * @param AuthenticationUtils $authenticationUtils
   *
   * @return void
   */
  public function setAuthenticationUtils(AuthenticationUtils $authenticationUtils): void {
    $this->authenticationUtils = $authenticationUtils;
  }

}
