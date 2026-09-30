<?php

namespace HBM\BasicsBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use HBM\BasicsBundle\Entity\Interfaces\ExtendedEntityRepo;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<T>
 *
 * @template-implements ExtendedEntityRepo<T>
 */
abstract class AbstractServiceEntityRepo extends ServiceEntityRepository implements ExtendedEntityRepo
{
    use ExtendedEntityRepoTrait;
}
