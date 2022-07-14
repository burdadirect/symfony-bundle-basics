<?php

namespace HBM\BasicsBundle\Security\Authenticator\Passport\Badge;

use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

class ImplicitLoginBadge implements BadgeInterface {

  private string $mode;

  /**
   * @param string $mode
   */
  public function __construct(string $mode) {
    $this->mode = $mode;
  }

  /**
   * @return string
   */
  public function getMode(): string {
    return $this->mode;
  }

  /**
   * @return bool
   */
  public function isResolved(): bool {
    return true;
  }
}
