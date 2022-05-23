<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\HttpFoundation\RequestStack;

trait RequestStackDependencyTrait {

  protected RequestStack $requestStack;

  /**
   * @required
   *
   * @param RequestStack $requestStack
   *
   * @return void
   */
  public function setRequestStack(RequestStack $requestStack): void {
    $this->requestStack = $requestStack;
  }

}
