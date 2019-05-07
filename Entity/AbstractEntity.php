<?php

namespace HBM\BasicsBundle\Entity;

use HBM\BasicsBundle\Entity\Interfaces\Timestampable;
use HBM\BasicsBundle\Entity\Interfaces\Uuidable;
use HBM\BasicsBundle\Entity\Traits\TimestampableTrait;
use HBM\BasicsBundle\Entity\Traits\UuidableTrait;

abstract class AbstractEntity implements Uuidable, Timestampable {

  use UuidableTrait, TimestampableTrait;

  /**
   * @var int
   */
  protected $id;

  /**
   * Construct entity and set UUID.
   */
  public function __construct() {
    $this->setUuid();
  }

  /**
   * Get id.
   *
   * @return int|null
   */
  public function getId() : ?int {
    return $this->id;
  }

  /**
   * Gets a zero padded id.
   *
   * @return string
   */
  public function getIdPadded() : string {
    return str_pad($this->getId(), 11, '0', STR_PAD_LEFT);
  }

}
