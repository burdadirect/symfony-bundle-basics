<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use HBM\BasicsBundle\Service\FormHelper;

trait FormHelperDependencyTrait {

  protected FormHelper $formHelper;

  /**
   * @required
   *
   * @param FormHelper $formHelper
   *
   * @return void
   */
  public function setFormHelper(FormHelper $formHelper): void {
    $this->formHelper = $formHelper;
  }

}
