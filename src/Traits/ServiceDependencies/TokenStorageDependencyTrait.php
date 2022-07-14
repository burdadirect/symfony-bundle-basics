<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

trait TokenStorageDependencyTrait {

  protected TokenStorageInterface $tokenStorage;

  /**
   * @required
   *
   * @param TokenStorageInterface $tokenStorage
   *
   * @return void
   */
  public function setTokenStorage(TokenStorageInterface $tokenStorage): void {
    $this->tokenStorage = $tokenStorage;
  }

}
