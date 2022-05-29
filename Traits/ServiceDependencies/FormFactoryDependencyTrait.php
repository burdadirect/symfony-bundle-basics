<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\Form\FormFactoryInterface;

trait FormFactoryDependencyTrait {

  protected FormFactoryInterface $formFactory;

  /**
   * @required
   *
   * @param FormFactoryInterface $formFactory
   *
   * @return void
   */
  public function setFormFactory(FormFactoryInterface $formFactory): void {
    $this->formFactory = $formFactory;
  }

}
