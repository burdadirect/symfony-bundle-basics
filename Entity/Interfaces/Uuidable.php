<?php

namespace HBM\Basics\Entity\Interfaces;

interface Uuidable extends Addressable {

  /**
   * Get UUID
   *
   * @return string
   */
  public function getUuid() : string;

}
