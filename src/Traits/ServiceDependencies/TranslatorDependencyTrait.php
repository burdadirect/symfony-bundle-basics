<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Contracts\Translation\TranslatorInterface;

trait TranslatorDependencyTrait {

  protected TranslatorInterface $translator;

  /**
   * @required
   *
   * @param TranslatorInterface $translator
   *
   * @return void
   */
  public function setTranslator(TranslatorInterface $translator): void {
    $this->translator = $translator;
  }

}
