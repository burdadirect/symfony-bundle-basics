<?php

namespace HBM\BasicsBundle\Util\Wording;

class EntityDeletionWording extends EntityWording {

  public function confirmTitle() : string {
    return 'Bitte bestätigen Sie, dass '.$this->labelHtml('text-primary').' gelöscht werden soll.';
  }

  public function confirmDeletionSuccess(array $unlinkedFiles = []) : string {
    $entityNominative = $this->labelHtml(NULL, NULL, TRUE);

    $message = $entityNominative.' wurde gelöscht.';
    if (\count($unlinkedFiles) > 0) {
      $message = $entityNominative.' und die zugehörige(n) '.\count($unlinkedFiles).' Datei(en) wurden gelöscht.';
    }

    return $message;
  }

}
