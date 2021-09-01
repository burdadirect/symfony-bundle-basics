<?php

namespace HBM\BasicsBundle\Util\Result;

use HBM\BasicsBundle\Util\Data\Level;

class Message {

  private ?string $level;

  private ?string $message;

  /**
   * Message constructor.
   *
   * @param string $message
   * @param string $level
   */
  public function __construct(string $message, string $level = Level::INFO) {
    $this->message = $message;
    $this->level = $level;
  }

  /**
   * Set level.
   *
   * @param string|null $level
   *
   * @return self
   */
  public function setLevel(?string $level) : self {
    $this->level = $level;

    return $this;
  }

  /**
   * Get level.
   *
   * @return string|null
   */
  public function getLevel() : ?string {
    return $this->level;
  }

  /**
   * Set message.
   *
   * @param string|null $message
   *
   * @return self
   */
  public function setMessage(?string $message) : self {
    $this->message = $message;

    return $this;
  }

  /**
   * Get message.
   *
   * @return string|null
   */
  public function getMessage() : ?string {
    return $this->message;
  }

  /****************************************************************************/
  /* CUSTOM                                                                   */
  /****************************************************************************/

  /**
   * @return string|null
   */
  public function formatMessage() : ?string {
    if (func_num_args() > 0) {
      return sprintf($this->getMessage(), ...func_get_args());
    }
    return $this->getMessage();
  }

  /**
   * @return string
   */
  public function getAlertLevel() : string {
    return Level::getAlertLevel($this->getLevel());
  }

}
