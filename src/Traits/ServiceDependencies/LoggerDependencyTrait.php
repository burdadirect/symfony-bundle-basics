<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Psr\Log\LoggerInterface;

trait LoggerDependencyTrait {

  protected LoggerInterface $logger;

  /**
   * @required
   *
   * @param LoggerInterface $logger
   *
   * @return void
   */
  public function setLogger(LoggerInterface $logger): void {
    $this->logger = $logger;
  }

}
