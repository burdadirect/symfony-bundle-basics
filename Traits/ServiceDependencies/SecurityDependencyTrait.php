<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\Security\Core\Security;

trait SecurityDependencyTrait {

  protected Security $security;

  /**
   * @required
   *
   * @param Security $security
   *
   * @return void
   */
  public function setSecurity(Security $security): void {
    $this->security = $security;
  }

}
