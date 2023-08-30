<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;

trait SecurityDependencyTrait
{
    protected Security $security;

    #[Required]
    public function setSecurity(Security $security): void
    {
        $this->security = $security;
    }
}
