<?php

namespace HBM\BasicsBundle\Util\Result;

use HBM\BasicsBundle\Entity\Interfaces\Uuidable;
use HBM\BasicsBundle\Util\Data\Level;

class Message {

  /**
   * @var string
   */
  private $level;

  /**
   * @var string
   */
  private $message;

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
   * @param string $level
   *
   * @return self
   */
  public function setLevel($level) : self {
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
   * @param string $message
   *
   * @return self
   */
  public function setMessage($message) : self {
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
