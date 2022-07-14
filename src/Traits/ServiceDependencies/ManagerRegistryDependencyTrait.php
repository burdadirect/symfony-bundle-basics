<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Doctrine\Persistence\ManagerRegistry;

trait ManagerRegistryDependencyTrait {

  protected ManagerRegistry $doctrine;

  /**
   * @required
   *
   * @param ManagerRegistry $doctrine
   *
   * @return void
   */
  public function setDoctrine(ManagerRegistry $doctrine): void {
    $this->doctrine = $doctrine;
  }

}
