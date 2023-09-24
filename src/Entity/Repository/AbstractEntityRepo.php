<?php

namespace HBM\BasicsBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use HBM\BasicsBundle\Entity\AbstractEntity;
use HBM\BasicsBundle\Entity\Interfaces\ExtendedEntityRepo;

abstract class AbstractEntityRepo extends EntityRepository implements ExtendedEntityRepo
{
    public function updateFields(AbstractEntity $entity, array $fields)
    {
        $qb = $this->createQueryBuilder('x');
        $qb->update();
        foreach ($fields as $fieldKey => $fielValue) {
            $qb->set('x.' . $fieldKey, ':' . $fieldKey)->setParameter($fieldKey, $fielValue);
        }
        $qb->where($qb->expr()->eq('x.id', ':id'))->setParameter('id', $entity->getId());

        return $qb->getQuery()->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function findRandomBy(array $criteria, $limit = null): array
    {
        try {
            $limitCap     = ($limit === null) ? 0 : $limit;
            $randomOffset = random_int(0, max(0, $this->count($criteria) - $limitCap));
        } catch (\Exception $e) {
            $randomOffset = 0;
        }

        return $this->findBy($criteria, [], $limit, $randomOffset);
    }

    /**
     * {@inheritDoc}
     */
    public function addSortations(QueryBuilder $qb, array $sortations, array $default = []): QueryBuilder
    {
        if (count($sortations) === 0) {
            $sortations = $default;
        }

        foreach ($sortations as $key => $value) {
            $qb->addOrderBy($key, $value);
        }

        return $qb;
    }

    /**
     * {@inheritDoc}
     */
    public function searchValue(QueryBuilder $qb, string $alias, string $field, $value = null, string $prefix = 'value'): QueryBuilder
    {
        if ($value !== null) {
            $qb->andWhere($qb->expr()->eq($alias . '.' . $field, ':' . $prefix))->setParameter($prefix, $value);
        }

        return $qb;
    }

    /**
     * {@inheritDoc}
     */
    public function searchChoices(QueryBuilder $qb, string $alias, string $field, array $choices = null, string $prefix = 'choices'): QueryBuilder
    {
        if (count($choices) > 0) {
            $qb->andWhere($qb->expr()->in($alias . '.' . $field, ':' . $prefix))->setParameter($prefix, $choices);
        }

        return $qb;
    }

    /**
     * {@inheritDoc}
     */
    public function searchSelection(QueryBuilder $qb, string $alias, string $field, array $selections = null, string $prefix = 'selections'): QueryBuilder
    {
        if (count($selections) > 0) {
            $qb->andWhere($qb->expr()->in($alias . '.' . $field, ':' . $prefix))->setParameter($prefix, $selections);
        } else {
            $qb->andWhere('1 = 0');
        }

        return $qb;
    }

    /**
     * {@inheritDoc}
     */
    public function searchNull(QueryBuilder $qb, string $alias, string $field, bool $isNull = null): QueryBuilder
    {
        if ($isNull === true) {
            $qb->andWhere($qb->expr()->isNull($alias . '.' . $field));
        } elseif ($isNull === false) {
            $qb->andWhere($qb->expr()->isNotNull($alias . '.' . $field));
        }

        return $qb;
    }

    /**
     * {@inheritDoc}
     */
    public function addSearchFields(QueryBuilder $qb, array $fields, array $words, string $prefix = 'search', string $format = '%%%s%%', string $method = 'like'): QueryBuilder
    {
        $and = $qb->expr()->andX();

        $counter = 0;
        foreach ($words as $word) {
            $or = $qb->expr()->orX();

            foreach ($fields as $field) {
                if ($method === 'eq') {
                    $or->add($qb->expr()->eq($field, ':' . $prefix . $counter));
                } else {
                    $or->add($qb->expr()->like($field, ':' . $prefix . $counter));
                }
            }

            $qb->setParameter($prefix . $counter, sprintf($format, $word));
            ++$counter;

            $and->add($or);
        }

        if ($and->count() > 0) {
            $qb->andWhere($and);
        }

        return $qb;
    }

    /**
     * {@inheritDoc}
     */
    public function leftJoinOnce(QueryBuilder $qb, string $alias, string $field, string $joinAlias): QueryBuilder
    {
        /** @var Join[] $joins */
        $joins = $qb->getDQLPart('join')[$alias] ?? [];

        $joinColumn = $alias . '.' . $field;
        foreach ($joins as $join) {
            if (($join->getJoin() === $joinColumn) && ($join->getAlias() === $joinAlias)) {
                return $qb;
            }
        }

        $qb->leftJoin($joinColumn, $joinAlias);

        return $qb;
    }
}
