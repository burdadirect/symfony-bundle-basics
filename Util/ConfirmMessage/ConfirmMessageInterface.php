<?php

namespace HBM\BasicsBundle\Util\ConfirmMessage;

use HBM\BasicsBundle\Entity\AbstractEntity;

interface ConfirmMessageInterface {

  /**
   * @param AbstractEntity $item
   *
   * @return string|null
   */
  public function evalIcon(AbstractEntity $item) : ?string;

}
