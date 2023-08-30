<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait EntityManagerDependencyTrait
{
    protected EntityManagerInterface $em;

    #[Required]
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->em = $entityManager;
    }
}
