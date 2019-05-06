<?php

namespace HBM\Basics\Entity\Interfaces;

interface NoticeInterface extends Addressable {

  /**
   * Get title.
   *
   * @return string|null
   */
  public function getTitle() : ?string;

  /**
   * Get message.
   *
   * @return string|null
   */
  public function getMessage() : ?string;

  /**
   * Get alert level.
   *
   * @return string
   */
  public function getAlertLevel() : string;

}
