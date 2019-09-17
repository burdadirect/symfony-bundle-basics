<?php

namespace HBM\BasicsBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;
use Symfony\Bridge\Doctrine\RegistryInterface;

abstract class AbstractDoctrineHelper {

  /**
   * @var RegistryInterface
   */
  private $doctrine;

  /**
   * AbstractDoctrineHelper constructor.
   *
   * @param RegistryInterface $doctrine
   */
  public function __construct(RegistryInterface $doctrine) {
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
