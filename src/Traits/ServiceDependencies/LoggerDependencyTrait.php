<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait LoggerDependencyTrait {

  protected LoggerInterface $logger;

  /**
   * @param LoggerInterface $logger
   *
   * @return void
   */
  #[Required]
  public function setLogger(LoggerInterface $logger): void {
    $this->logger = $logger;
  }

}
