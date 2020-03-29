<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

interface SettingInterface extends Addressable {

  /**
   * Get varType.
   *
   * @return string|null
   */
  public function getVarType() : ?string;

  /**
   * Get varValueParsed.
   * 
   * @return mixed
   */
  public function getVarValueParsed() : ?string;

}
