<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait SecurityRoleHierarchyDependencyTrait
{
    protected RoleHierarchyInterface $roleHierarchy;

    #[Required]
    public function setRoleHierarchy(RoleHierarchyInterface $roleHierarchy): void
    {
        $this->roleHierarchy = $roleHierarchy;
    }
}
