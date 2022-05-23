<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Doctrine\ORM\EntityManagerInterface;

trait EntityManagerDependencyTrait {

  protected EntityManagerInterface $em;

  /**
   * @required
   *
   * @param EntityManagerInterface $entityManager
   *
   * @return void
   */
  public function setEntityManager(EntityManagerInterface $entityManager): void {
    $this->em = $entityManager;
  }

}
