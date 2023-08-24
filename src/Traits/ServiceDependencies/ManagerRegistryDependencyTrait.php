<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Service\Attribute\Required;

trait ManagerRegistryDependencyTrait {

  protected ManagerRegistry $doctrine;

  /**
   * @param ManagerRegistry $doctrine
   *
   * @return void
   */
  #[Required]
  public function setDoctrine(ManagerRegistry $doctrine): void {
    $this->doctrine = $doctrine;
  }

}
