<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Service\Attribute\Required;

trait RequestStackDependencyTrait {

  protected RequestStack $requestStack;

  /**
   * @param RequestStack $requestStack
   *
   * @return void
   */
  #[Required]
  public function setRequestStack(RequestStack $requestStack): void {
    $this->requestStack = $requestStack;
  }

}
