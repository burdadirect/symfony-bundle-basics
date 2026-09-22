<?php

namespace HBM\BasicsBundle\Entity\Repository;

use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use HBM\BasicsBundle\Entity\AbstractEntity;
use HBM\BasicsBundle\ORM\Query\Expr;

/**
 * @method QueryBuilder createQueryBuilder(string $alias, string|null $indexBy = null)
 * @method int          count(array $criteria = [])
 * @method array        findBy(array $criteria, array|null $orderBy = null, int|null $limit = null, int|null $offset = null)
 */
trait ExtendedEntityRepoTrait
{
    public function createQueryBuilderForAlias(string $alias): QueryBuilder
    {
        return $this->createQueryBuilder($alias);
    }

    public static function uniqueParam(?string $prefix = null, ?string $postfix = null): string
    {
        return str_replace('.', '', $prefix . uniqid('', true) . $postfix);
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

    public function findRandomBy(array $criteria, ?int $limit = null): array
    {
        try {
            $limitCap     = $limit ?? 0;
            $randomOffset = random_int(0, max(0, $this->count($criteria) - $limitCap));
        } catch (\Exception) {
            $randomOffset = 0;
        }

        return $this->findBy($criteria, [], $limit, $randomOffset);
    }

    public function addSortations(QueryBuilder $qb, array $sortations, array $default = [], bool $forceDefaults = true): QueryBuilder
    {
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

    public function searchFromThru(QueryBuilder $qb, string $field, \DateTime $from, \DateTime $thru): QueryBuilder
    {
        $paramNameFrom = self::uniqueParam('from');
        $paramNameThru = self::uniqueParam('thru');

        $qb->andWhere($qb->expr()->between($field, ':' . $paramNameFrom, ':' . $paramNameThru));
        $qb->setParameter($paramNameFrom, $from);
        $qb->setParameter($paramNameThru, $thru);

        return $qb;
    }

    public function searchValue(QueryBuilder $qb, string $alias, string $field, mixed $value = null, string $prefix = 'value'): QueryBuilder
    {
        if ($value !== null) {
            $paramName = self::uniqueParam($prefix);
            $qb->andWhere($qb->expr()->eq($alias . '.' . $field, ':' . $paramName))->setParameter($paramName, $value);
        }

        return $qb;
    }

    public function searchChoices(QueryBuilder $qb, string $alias, string $field, ?array $choices = null, string $prefix = 'choices'): QueryBuilder
    {
        if (count($choices) > 0) {
            $paramName = self::uniqueParam($prefix);
            $qb->andWhere($qb->expr()->in($alias . '.' . $field, ':' . $paramName))->setParameter($paramName, $choices);
        }

        return $qb;
    }

    public function searchManyToMany(QueryBuilder $qb, string $alias, string $field, string $joinAlias, ?array $relations = null, string $prefix = 'relations'): QueryBuilder
    {
        if (count($relations) > 0) {
            $this->leftJoinOnce($qb, $alias, $field, $joinAlias);
            $paramName = self::uniqueParam($prefix);
            $qb->andWhere($qb->expr()->in($joinAlias, ':' . $paramName))->setParameter($paramName, $relations);
        }

        return $qb;
    }

    public function searchManyToManyNull(QueryBuilder $qb, string $alias, string $field, string $joinAlias, ?bool $isNull = null): QueryBuilder
    {
        if ($isNull === true) {
            $this->leftJoinOnce($qb, $alias, $field, $joinAlias);
            $qb->andWhere($qb->expr()->isNull($joinAlias));
        } elseif ($isNull === false) {
            $qb->andWhere($qb->expr()->isNotNull($joinAlias));
        }

        return $qb;
    }

    public function searchSelection(QueryBuilder $qb, string $alias, string $field, ?array $selections = null, string $prefix = 'selections'): QueryBuilder
    {
        if (count($selections) > 0) {
            $paramName = self::uniqueParam($prefix);
            $qb->andWhere($qb->expr()->in($alias . '.' . $field, ':' . $paramName))->setParameter($paramName, $selections);
        } else {
            $qb->andWhere('1 = 0');
        }

        return $qb;
    }

    public function searchNull(QueryBuilder $qb, string $alias, string $field, ?bool $isNull = null): QueryBuilder
    {
        if ($isNull === true) {
            $qb->andWhere($qb->expr()->isNull($alias . '.' . $field));
        } elseif ($isNull === false) {
            $qb->andWhere($qb->expr()->isNotNull($alias . '.' . $field));
        }

        return $qb;
    }

    public function searchJson(QueryBuilder $qb, string $alias, string $field, string $value, ?Composite $composite = null, string $paramPrefix = 'searchJson_'): QueryBuilder
    {
        $paramName = self::uniqueParam($paramPrefix);
        $expr      = $qb->expr()->like($alias . '.' . $field, ':' . $paramName) . Expr::escapeSequence();
        $composite ? $composite->add($expr) : $qb->andWhere($expr);
        $qb->setParameter($paramName, '%"' . Expr::escapeLike($value) . '"%');

        return $qb;
    }

    public function searchJsonArray(QueryBuilder $qb, string $alias, string $field, array $values, bool $all = false): QueryBuilder
    {
        $conds = $all ? $qb->expr()->andX() : $qb->expr()->orX();

        foreach ($values as $index => $value) {
            $this->searchJson($qb, $alias, $field, $value, $conds, 'searchJson' . $index . '_');
        }

        if ($conds->count() > 0) {
            $qb->andWhere($conds);
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
            $paramName  = self::uniqueParam($prefix . $counter);
            $condFields = $allFields ? $qb->expr()->andX() : $qb->expr()->orX();

            foreach ($fields as $field) {
                if ($method === 'eq') {
                    $condFields->add($qb->expr()->eq($field, ':' . $paramName));
                } else {
                    $condFields->add($qb->expr()->like($field, ':' . $paramName) . Expr::escapeSequence());
                }
            }

            if ($method === 'eq') {
                $qb->setParameter($paramName, sprintf($format, $word));
            } else {
                $qb->setParameter($paramName, sprintf($format, Expr::escapeLike($word)));
            }

            ++$counter;

            $condWords->add($condFields);
        }

        return $condWords;
    }

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

    public function getIdsAndNamesSorted(callable|string $callback, array $criteria = [], array $sortation = []): array
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
        uasort($idsAndNames, strcasecmp(...));

        return $idsAndNames;
    }
}
