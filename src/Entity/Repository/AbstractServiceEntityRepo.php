<?php

namespace HBM\BasicsBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use HBM\BasicsBundle\Entity\AbstractEntity;
use HBM\BasicsBundle\Entity\Interfaces\ExtendedEntityRepo;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<T>
 */
abstract class AbstractServiceEntityRepo extends ServiceEntityRepository implements ExtendedEntityRepo
{
    use ExtendedEntityRepoTrait;

    /**
     * @return T[]
     */
    abstract public function findRandomBy(array $criteria = [], ?int $limit = null): array;

    /**
     * @return null|T
     */
    abstract public function findOneRandomBy(array $criteria = []);
}
