<?php

namespace HBM\BasicsBundle\Service;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;

abstract class AbstractDoctrineHelper {

  /**
   * @var ManagerRegistry
   */
  private $doctrine;

  /**
   * AbstractDoctrineHelper constructor.
   *
   * @param ManagerRegistry $doctrine
   */
  public function __construct(ManagerRegistry $doctrine) {
    $this->doctrine = $doctrine;
  }

  /****************************************************************************/
  /* OBJECT MANAGER                                                           */
  /****************************************************************************/

  /**
   * @param string|NULL $name
   *
   * @return ObjectManager
   */
  public function getOM(string $name = null) : ObjectManager {
    return $this->doctrine->getManager($name);
  }

  /**
   * @param string|NULL $name
   *
   * @return ObjectManager
   */
  public function resetOM(string $name = null) : ObjectManager {
    $this->doctrine->resetManager($name);

    return $this->getOM($name);
  }

  /**
   * @param string|NULL $name
   *
   * @return object|Connection
   */
  public function getConnection(string $name = null) {
    return $this->doctrine->getConnection($name);
  }

}
