<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Service\Attribute\Required;

trait SecurityDependencyTrait {

  protected Security $security;

  /**
   * @param Security $security
   *
   * @return void
   */
  #[Required]
  public function setSecurity(Security $security): void {
    $this->security = $security;
  }

}
