<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait TokenStorageDependencyTrait {

  protected TokenStorageInterface $tokenStorage;

  /**
   * @param TokenStorageInterface $tokenStorage
   *
   * @return void
   */
  #[Required]
  public function setTokenStorage(TokenStorageInterface $tokenStorage): void {
    $this->tokenStorage = $tokenStorage;
  }

}
