<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

interface Addressable {

  /**
   * Get id
   *
   * @return int|null
   */
  public function getId() : ?int;

  /**
   * Get UUID
   *
   * @return string
   */
  public function getUuid() : string;

}
