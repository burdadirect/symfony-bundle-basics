<?php

namespace HBM\BasicsBundle\Service;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use HBM\BasicsBundle\Traits\ServiceDependencies\ManagerRegistryDependencyTrait;

abstract class AbstractDoctrineHelper
{
    use ManagerRegistryDependencyTrait;

    /* OBJECT MANAGER */

    public function getOM(?string $name = null): ObjectManager
    {
        return $this->doctrine->getManager($name);
    }

    public function resetOM(?string $name = null): ObjectManager
    {
        $this->doctrine->resetManager($name);

        return $this->getOM($name);
    }

    /**
     * @return Connection|object
     */
    public function getConnection(?string $name = null): Connection
    {
        return $this->doctrine->getConnection($name);
    }
}
