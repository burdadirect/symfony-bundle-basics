<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait RouterDependencyTrait {

  protected RouterInterface $router;

  /**
   * @param RouterInterface $router
   *
   * @return void
   */
  #[Required]
  public function setRouter(RouterInterface $router): void {
    $this->router = $router;
  }

}
