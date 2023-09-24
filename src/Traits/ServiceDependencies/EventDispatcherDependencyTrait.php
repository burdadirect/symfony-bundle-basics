<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait EventDispatcherDependencyTrait
{
    protected EventDispatcherInterface $eventDispatcher;

    #[Required]
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }
}
