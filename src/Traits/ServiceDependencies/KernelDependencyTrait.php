<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait KernelDependencyTrait
{
    protected KernelInterface $kernel;

    #[Required]
    public function setKernel(KernelInterface $kernel): void
    {
        $this->kernel = $kernel;
    }
}
