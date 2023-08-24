<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Contracts\Service\Attribute\Required;
use Twig\Environment;

trait TwigEnvironmentDependencyTrait {

  protected Environment $twig;

  /**
   * @param Environment $twig
   *
   * @return void
   */
  #[Required]
  public function setTwigEnvironment(Environment $twig): void {
    $this->twig = $twig;
  }

}
