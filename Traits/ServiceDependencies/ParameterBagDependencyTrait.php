<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

trait ParameterBagDependencyTrait {

  protected ParameterBagInterface $pb;

  /**
   * @required
   *
   * @param ParameterBagInterface $parameterBag
   *
   * @return void
   */
  public function setParameterBag(ParameterBagInterface $parameterBag): void {
    $this->pb = $parameterBag;
  }

}
