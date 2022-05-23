<?php

namespace HBM\BasicsBundle\Service;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use HBM\BasicsBundle\Traits\ServiceDependencies\ManagerRegistryDependencyTrait;

abstract class AbstractDoctrineHelper {

  use ManagerRegistryDependencyTrait;

  /****************************************************************************/
  /* OBJECT MANAGER                                                           */
  /****************************************************************************/

  /**
   * @param string|NULL $name
   *
   * @return ObjectManager
   */
  public function getOM(string $name = null): ObjectManager {
    return $this->doctrine->getManager($name);
  }

  /**
   * @param string|NULL $name
   *
   * @return ObjectManager
   */
  public function resetOM(string $name = null): ObjectManager {
    $this->doctrine->resetManager($name);

    return $this->getOM($name);
  }

  /**
   * @param string|NULL $name
   *
   * @return object|Connection
   */
  public function getConnection(string $name = null): Connection {
    return $this->doctrine->getConnection($name);
  }

}
