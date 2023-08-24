<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait UserPasswordHasherDependencyTrait {

  protected UserPasswordHasherInterface $userPasswordHasher;

  /**
   * @param UserPasswordHasherInterface $userPasswordHasher
   *
   * @return void
   */
  #[Required]
  public function setUserPasswordHasher(UserPasswordHasherInterface $userPasswordHasher): void {
    $this->userPasswordHasher = $userPasswordHasher;
  }

}
