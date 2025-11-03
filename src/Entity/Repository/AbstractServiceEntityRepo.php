<?php

namespace HBM\BasicsBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use HBM\BasicsBundle\Entity\Interfaces\ExtendedEntityRepo;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<T>
 */
abstract class AbstractServiceEntityRepo extends ServiceEntityRepository implements ExtendedEntityRepo
{
    use ServiceEntityRepoTrait;
}
