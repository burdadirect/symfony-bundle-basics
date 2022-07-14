<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

trait UserPasswordHasherDependencyTrait {

  protected UserPasswordHasherInterface $userPasswordHasher;

  /**
   * @required
   *
   * @param UserPasswordHasherInterface $userPasswordHasher
   *
   * @return void
   */
  public function setUserPasswordHasher(UserPasswordHasherInterface $userPasswordHasher): void {
    $this->userPasswordHasher = $userPasswordHasher;
  }

}
