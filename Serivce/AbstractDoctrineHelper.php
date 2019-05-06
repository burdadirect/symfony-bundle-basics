<?php

namespace HBM\Basics\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AbstractDoctrineHelper {

  /**
   * @var RegistryInterface
   */
  private $doctrine;

  /**
   * @var ObjectManager
   */
  private $objectManager;

  /**
   * DoctrineHelper constructor.
   *
   * @param RegistryInterface $doctrine
   */
  public function __construct(RegistryInterface $doctrine) {
    $this->doctrine = $doctrine;
    $this->objectManager = $this->doctrine->getManager();
  }

  /****************************************************************************/
  /* OBJECT MANAGER                                                           */
  /****************************************************************************/

  /**
   * @return ObjectManager
   */
  public function getOM() : ObjectManager {
    return $this->objectManager;
  }

  /**
   * @return ObjectManager
   */
  public function resetOM() : ObjectManager {
    $this->doctrine->resetManager();
    $this->objectManager = $this->doctrine->getManager();

    return $this->objectManager;
  }

}
