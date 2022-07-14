<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Psr\EventDispatcher\EventDispatcherInterface;

trait EventDispatcherDependencyTrait {

  protected EventDispatcherInterface $eventDispatcher;

  /**
   * @required
   *
   * @param EventDispatcherInterface $eventDispatcher
   *
   * @return void
   */
  public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void {
    $this->eventDispatcher = $eventDispatcher;
  }

}
