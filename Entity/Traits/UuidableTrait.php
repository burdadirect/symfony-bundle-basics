<?php

namespace HBM\Basics\Entity\Traits;

trait UuidableTrait {

  /**
   * @var string
   */
  protected $uuid;

  /**
   * Set GUID (only alphanumeric, due to restrictions of third party apis).
   *
   * @return self
   */
  public function setUuid() : self {
    if ($this->uuid === NULL) {
      try {
        $uuid = uniqid('', TRUE).random_int(1000, 9999);
      } catch (\Exception $e) {
        $uuid = uniqid('', TRUE).'5000';
      }
      $this->uuid = preg_replace('/[^A-Za-z0-9]/', '', $uuid);
    }

    return $this;
  }

  /**
   * Get UUID
   *
   * @return string
   */
  public function getUuid() : string {
    return $this->uuid;
  }

}
