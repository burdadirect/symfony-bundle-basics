<?php

namespace HBM\BasicsBundle\Util\Wording;

/**
 * @deprecated Use EntityWording instead.
 */
class EntityDeletionWording extends EntityWording {

  public function confirmTitle() : string {
    return parent::confirmDeletionTitle();
  }

  public function confirmDeletionSuccess(array $unlinkedFiles = []) : string {
    return parent::confirmDeletionSuccess($unlinkedFiles);
  }

}
