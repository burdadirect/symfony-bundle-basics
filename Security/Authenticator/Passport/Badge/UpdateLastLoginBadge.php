<?php

namespace HBM\BasicsBundle\Security\Authenticator\Passport\Badge;

use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

class UpdateLastLoginBadge implements BadgeInterface {

  private bool $flag;

  /**
   * @param bool $flag
   */
  public function __construct(bool $flag = true) {
    $this->flag = $flag;
  }

  /**
   * @return bool
   */
  public function getFlag(): bool {
    return $this->flag;
  }

  /**
   * @return bool
   */
  public function isResolved(): bool {
    return true;
  }
}
