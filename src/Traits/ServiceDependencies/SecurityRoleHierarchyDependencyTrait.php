<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

trait SecurityRoleHierarchyDependencyTrait {

  protected RoleHierarchyInterface $roleHierarchy;

  /**
   * @required
   *
   * @param RoleHierarchyInterface $roleHierarchy
   *
   * @return void
   */
  public function setRoleHierarchy(RoleHierarchyInterface $roleHierarchy): void {
    $this->roleHierarchy = $roleHierarchy;
  }

}
