<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Service\Attribute\Required;

trait AuthenticationUtilsDependencyTrait {

  protected AuthenticationUtils $authenticationUtils;

  /**
   * @param AuthenticationUtils $authenticationUtils
   *
   * @return void
   */
  #[Required]
  public function setAuthenticationUtils(AuthenticationUtils $authenticationUtils): void {
    $this->authenticationUtils = $authenticationUtils;
  }

}
