<?php

namespace HBM\BasicsBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use HBM\BasicsBundle\Entity\AbstractEntity;
use HBM\BasicsBundle\Entity\Interfaces\ExtendedEntityRepo;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<T>
 */
abstract class AbstractServiceEntityRepo extends ServiceEntityRepository implements ExtendedEntityRepo
{

    public static function uniqueParam(?string $prefix = NULL, ?string $postfix = NULL): string {
        return str_replace('.', '', $prefix.uniqid('', TRUE).$postfix);
    }

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
            $limitCap     = $limit ?? 0;
            $randomOffset = random_int(0, max(0, $this->count($criteria) - $limitCap));
        } catch (\Exception $e) {
            $randomOffset = 0;
        }

        return $this->findBy($criteria, [], $limit, $randomOffset);
    }

    /**
     * {@inheritDoc}
     */
    public function addSortations(QueryBuilder $qb, array $sortations, array $default = [], bool $forceDefaults = true): QueryBuilder {
        foreach ($sortations as $key => $value) {
            $qb->addOrderBy($key, $value);
        }

        if ($forceDefaults || (\count($sortations) === 0)) {
            foreach ($default as $key => $value) {
                $qb->addOrderBy($key, $value);
            }
        }

        return $qb;
    }

    public function searchFromThru(QueryBuilder $qb, string $field, \DateTime $from, \DateTime $thru): QueryBuilder {
        $paramNameFrom = self::uniqueParam('from');
        $paramNameThru = self::uniqueParam('thru');

        $qb->andWhere($qb->expr()->between($field, ':'.$paramNameFrom, ':'.$paramNameThru));
        $qb->setParameter($paramNameFrom, $from);
        $qb->setParameter($paramNameThru, $thru);
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

    public function searchManyToMany(QueryBuilder $qb, string $alias, string $field, string $joinAlias, array $relations = null, string $prefix = 'relations'): QueryBuilder
    {
        if (count($relations) > 0) {
            $this->leftJoinOnce($qb, $alias, $field, $joinAlias);
            $qb->andWhere($qb->expr()->in($joinAlias, ':' . $prefix))->setParameter($prefix, $relations);
        }

        return $qb;
    }

    public function searchManyToManyNull(QueryBuilder $qb, string $alias, string $field, string $joinAlias, bool $isNull = null): QueryBuilder
    {
        if ($isNull === true) {
            $this->leftJoinOnce($qb, $alias, $field, $joinAlias);
            $qb->andWhere($qb->expr()->isNull($joinAlias));
        } elseif ($isNull === false) {
            $qb->andWhere($qb->expr()->isNotNull($joinAlias));
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
     * @param array|Composite[] $condGroups
     */
    public function addCondGroup(QueryBuilder $qb, array $condGroups, bool $all = false): QueryBuilder
    {
        $conds = $all ? $qb->expr()->andX() : $qb->expr()->orX();

        foreach ($condGroups as $condGroup) {
            if ($condGroup->count() > 0) {
                $conds->add($condGroup);
            }
        }

        if ($conds->count() > 0) {
            $qb->andWhere($conds);
        }

        return $qb;
    }

    /**
     * {@inheritDoc}
     */
    public function addSearchFields(QueryBuilder $qb, array $fields, array $words, string $prefix = 'search', string $format = '%%%s%%', string $method = 'like', bool $allWords = true, bool $allFields = false): QueryBuilder
    {
        $condWords = $this->getSearchFieldsConditions($qb, $fields, $words, $prefix, $format, $method, $allWords, $allFields);

        if ($condWords->count() > 0) {
            $qb->andWhere($condWords);
        }

        return $qb;
    }

    public function getSearchFieldsConditions(QueryBuilder $qb, array $fields, array $words, string $prefix = 'search', string $format = '%%%s%%', string $method = 'like', bool $allWords = true, bool $allFields = false): Composite
    {
        $condWords = $allWords ? $qb->expr()->andX() : $qb->expr()->orX();

        $counter = 0;
        foreach ($words as $word) {
            $condFields = $allFields ? $qb->expr()->andX() : $qb->expr()->orX();

            foreach ($fields as $field) {
                if ($method === 'eq') {
                    $condFields->add($qb->expr()->eq($field, ':' . $prefix . $counter));
                } else {
                    $condFields->add($qb->expr()->like($field, ':' . $prefix . $counter));
                }
            }

            $qb->setParameter($prefix . $counter, sprintf($format, $word));
            ++$counter;

            $condWords->add($condFields);
        }

        return $condWords;
    }

    /**
     * {@inheritDoc}
     */
    public function leftJoinOnce(QueryBuilder $qb, string $alias, string $field, string $joinAlias, $conditionType = null, $condition = null, $indexBy = null): QueryBuilder
    {
        /** @var Join[] $joins */
        $joins = $qb->getDQLPart('join')[$alias] ?? [];

        $joinColumn = $alias . '.' . $field;
        foreach ($joins as $join) {
            if (($join->getJoin() === $joinColumn) && ($join->getAlias() === $joinAlias)) {
                return $qb;
            }
        }

        $qb->leftJoin($joinColumn, $joinAlias, $conditionType, $condition, $indexBy);

        return $qb;
    }

    /**
     * @param callable|string $callback
     */
    public function getIdsAndNamesSorted($callback, array $criteria = [], array $sortation = []): array
    {
        $idsAndNames = [];

        $items = $this->findBy($criteria, $sortation);
        foreach ($items as $item) {
            $name = null;

            if (is_string($callback)) {
                $name = $item->{$callback}();
            } elseif (is_callable($callback)) {
                $name = $callback($item);
            }

            $idsAndNames[$item->getId()] = $name;
        }
        uasort($idsAndNames, 'strcasecmp');

        return $idsAndNames;
    }
}
