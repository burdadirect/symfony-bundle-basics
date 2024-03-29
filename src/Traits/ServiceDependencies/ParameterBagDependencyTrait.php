<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait ParameterBagDependencyTrait
{
    protected ParameterBagInterface $pb;

    #[Required]
    public function setParameterBag(ParameterBagInterface $parameterBag): void
    {
        $this->pb = $parameterBag;
    }
}
