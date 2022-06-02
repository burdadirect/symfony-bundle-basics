<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;

trait FormLoginAuthenticatorDependencyTrait {

  protected FormLoginAuthenticator $formLoginAuthenticator;

  /**
   * @required
   *
   * @param FormLoginAuthenticator $formLoginAuthenticator
   *
   * @return void
   */
  public function setFormLoginAuthenticator(FormLoginAuthenticator $formLoginAuthenticator): void {
    $this->formLoginAuthenticator = $formLoginAuthenticator;
  }

}
