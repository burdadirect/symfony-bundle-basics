<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use HBM\BasicsBundle\Service\FormHelper;
use Symfony\Contracts\Service\Attribute\Required;

trait FormHelperDependencyTrait {

  protected FormHelper $formHelper;

  /**
   * @param FormHelper $formHelper
   *
   * @return void
   */
  #[Required]
  public function setFormHelper(FormHelper $formHelper): void {
    $this->formHelper = $formHelper;
  }

}
