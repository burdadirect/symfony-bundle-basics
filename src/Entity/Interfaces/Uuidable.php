<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

interface Uuidable extends Addressable {

  /**
   * Get UUID
   *
   * @return string
   */
  public function getUuid() : string;

}
