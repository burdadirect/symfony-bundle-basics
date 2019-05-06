<?php

namespace HBM\Basics\Util\Result;

use HBM\Basics\Entity\Interfaces\Uuidable;
use HBM\Basics\Entity\Traits\UuidableTrait;
use HBM\Basics\Util\Data\Level;

class Message implements Uuidable {

  use UuidableTrait;

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
   * @return int|null
   */
  public function getId(): ?int {
    return NULL;
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
   * @return string
   */
  public function getAlertLevel() : string {
    return Level::getAlertLevel($this->getLevel());
  }

}
