<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\Routing\RouterInterface;

trait RouterDependencyTrait {

  protected RouterInterface $router;

  /**
   * @required
   *
   * @param RouterInterface $router
   *
   * @return void
   */
  public function setRouter(RouterInterface $router): void {
    $this->router = $router;
  }

}
