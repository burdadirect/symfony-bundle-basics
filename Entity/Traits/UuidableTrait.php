<?php

namespace HBM\BasicsBundle\Entity\Traits;

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
  public function setUuid() {
    if ($this->uuid === NULL) {
      $this->uuid = $this->generateUuid();
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

  /**
   * @return string
   */
  protected function generateUuid() : string {
    try {
      $uuid = uniqid('', TRUE).random_int(1000, 9999);
    } catch (\Exception $e) {
      $uuid = uniqid('', TRUE).'5000';
    }
    return preg_replace('/[^A-Za-z0-9]/', '', $uuid);
  }

}
