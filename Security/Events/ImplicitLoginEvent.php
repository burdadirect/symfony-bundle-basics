<?php

namespace HBM\BasicsBundle\Security\Events;

use \Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class ImplicitLoginEvent
 *
 * This class is to determine an implicit (programatically) login event.
 */
class ImplicitLoginEvent extends InteractiveLoginEvent  {

  public const MODE_EMAIL_CONFIRMATION = 'email_confirmation';
  public const MODE_PASSWORD_RESET = 'password_reset';

  /**
   * @var string
   */
  private $mode;

  /**
   * Set mode.
   *
   * @param string $mode
   *
   * @return self
   */
  public function setMode(string $mode = NULL) : self {
    $this->mode = $mode;

    return $this;
  }

  /**
   * Get mode.
   *
   * @return string|null
   */
  public function getMode() : ?string {
    return $this->mode;
  }

}
