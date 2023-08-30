<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Service\Attribute\Required;

trait RequestStackDependencyTrait
{
    protected RequestStack $requestStack;

    #[Required]
    public function setRequestStack(RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
    }
}
