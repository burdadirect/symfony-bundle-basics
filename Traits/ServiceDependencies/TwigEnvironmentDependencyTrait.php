<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Twig\Environment;

trait TwigEnvironmentDependencyTrait {

  protected Environment $twig;

  /**
   * @required
   *
   * @param Environment $twig
   *
   * @return void
   */
  public function setTwigEnvironment(Environment $twig): void {
    $this->twig = $twig;
  }

}
