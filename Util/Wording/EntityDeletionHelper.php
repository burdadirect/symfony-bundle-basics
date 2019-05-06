<?php

namespace HBM\BasicsBundle\Util\Wording;

class EntityDeletionHelper extends EntityHelper {

  public function confirmTitle() : string {
    return 'Bitte bestätigen Sie, dass '.$this->entityLabel('text-primary').' gelöscht werden soll.';
  }

  public function confirmDeletionSuccess(array $unlinkedFiles = []) : string {
    $entityNominative = ucfirst($this->entityLabel());

    $message = $entityNominative.' wurde gelöscht.';
    if (\count($unlinkedFiles) > 0) {
      $message = $entityNominative.' und die zugehörige(n) '.\count($unlinkedFiles).' Datei(en) wurden gelöscht.';
    }

    return $message;
  }

}
