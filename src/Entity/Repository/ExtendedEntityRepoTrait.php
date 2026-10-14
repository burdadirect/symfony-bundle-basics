<?php

namespace HBM\BasicsBundle\Entity\Repository;

use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use HBM\BasicsBundle\Entity\AbstractEntity;
use HBM\BasicsBundle\ORM\Query\Expr;

/**
 * @method QueryBuilder createQueryBuilder(string $alias, string|null $indexBy = null)
 * @method \int         count(array $criteria = [])
 * @method \array       findBy(array $criteria, array|null $orderBy = null, int|null $limit = null, int|null $offset = null)
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

    public function findRandomBy(array $criteria = [], ?int $limit = null): array
    {
        try {
            $limitCap     = $limit ?? 0;
            $randomOffset = random_int(0, max(0, $this->count($criteria) - $limitCap));
        } catch (\Exception) {
            $randomOffset = 0;
        }

        return $this->findBy($criteria, [], $limit, $randomOffset);
    }

    public function findOneRandomBy(array $criteria = []): ?AbstractEntity
    {
        return $this->findRandomBy($criteria, 1)[0] ?? null;
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

    protected function searchInternal(QueryBuilder $qb, callable $expr, string $alias, string $field,  mixed $paramValue, string $prefix = '', ?Composite $composite = null): QueryBuilder
    {
        if (($paramValue === null) || (is_array($paramValue) && (count($paramValue) === 0))) {
            return $qb;
        }

        $paramName = self::uniqueParam($prefix);
        $comparison = $expr($alias . '.' . $field, ':' . $paramName);

        $composite ? $composite->add($comparison) : $qb->andWhere($comparison);
        $qb->setParameter($paramName, $paramValue);

        return $qb;
    }

    public function searchValue(QueryBuilder $qb, string $alias, string $field, mixed $value = null, string $prefix = 'value_', ?Composite $composite = null): QueryBuilder
    {
        return $this->searchInternal($qb, $qb->expr()->eq(...), $alias, $field, $value, $prefix, $composite);
    }

    public function searchChoices(QueryBuilder $qb, string $alias, string $field, ?array $choices = null, string $prefix = 'choices_', ?Composite $composite = null): QueryBuilder
    {
        return $this->searchInternal($qb, $qb->expr()->in(...), $alias, $field, $choices, $prefix, $composite);
    }

    public function searchDateFrom(QueryBuilder $qb, string $alias, string $field, ?\DateTime $from, string $prefix = 'from_', ?Composite $composite = null): QueryBuilder
    {
        return $this->searchInternal($qb, $qb->expr()->gte(...), $alias, $field, $from, $prefix, $composite);
    }

    public function searchDateThru(QueryBuilder $qb, string $alias, string $field, ?\DateTime $thru, string $prefix = 'thru_', ?Composite $composite = null): QueryBuilder
    {
        return $this->searchInternal($qb, $qb->expr()->lte(...), $alias, $field, $thru, $prefix, $composite);
    }

    public function searchDateFromThru(QueryBuilder $qb, string $alias, string $field, \DateTime $from, \DateTime $thru, ?Composite $composite = null): QueryBuilder
    {
        $paramNameFrom = self::uniqueParam('from');
        $paramNameThru = self::uniqueParam('thru');
        $expr = $qb->expr()->between($alias . '.' . $field, ':' . $paramNameFrom, ':' . $paramNameThru);

        $composite ? $composite->add($expr) : $qb->andWhere($expr);

        $qb->setParameter($paramNameFrom, $from);
        $qb->setParameter($paramNameThru, $thru);

        return $qb;
    }

    public function searchManyToMany(QueryBuilder $qb, string $alias, string $field, string $joinAlias, ?array $relations = null, string $prefix = 'relations_'): QueryBuilder
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

    public function searchSelection(QueryBuilder $qb, string $alias, string $field, ?array $selections = null, string $prefix = 'selections_', ?Composite $composite = null): QueryBuilder
    {
        if (count($selections) > 0) {
            $paramName = self::uniqueParam($prefix);
            $expr = $qb->expr()->in($alias . '.' . $field, ':' . $paramName);
            $qb->setParameter($paramName, $selections);
        } else {
            $expr = '1 = 0';
        }

        $composite ? $composite->add($expr) : $qb->andWhere($expr);

        return $qb;
    }

    public function searchNull(QueryBuilder $qb, string $alias, string $field, ?bool $isNull = null, ?Composite $composite = null): QueryBuilder
    {
        $expr = null;
        if ($isNull === true) {
            $expr = $qb->expr()->isNull($alias . '.' . $field);
        } elseif ($isNull === false) {
            $expr = $qb->expr()->isNotNull($alias . '.' . $field);
        }

        if ($expr) {
            $composite ? $composite->add($expr) : $qb->andWhere($expr);
        }

        return $qb;
    }

    private function searchLikeOrNot(callable $comparison, QueryBuilder $qb, string $alias, string $field, string $value, string $paramPrefix = 'searchLike_', ?Composite $composite = null, string $format = '%%%s%%'): QueryBuilder
    {
        $paramName = self::uniqueParam($paramPrefix);
        $expr      = $comparison($alias . '.' . $field, ':' . $paramName) . Expr::escapeSequence();

        $composite ? $composite->add($expr) : $qb->andWhere($expr);
        $qb->setParameter($paramName, sprintf($format, Expr::escapeLike($value)));

        return $qb;
    }

    public function searchNotLike(QueryBuilder $qb, string $alias, string $field, string $value, string $paramPrefix = 'searchNotLike_', ?Composite $composite = null, string $format = '%%%s%%'): QueryBuilder
    {
        return $this->searchLikeOrNot($qb->expr()->notLike(...), $qb, $alias, $field, $value, $paramPrefix, $composite, $format);
    }

    public function searchLike(QueryBuilder $qb, string $alias, string $field, string $value, string $paramPrefix = 'searchLike_', ?Composite $composite = null, string $format = '%%%s%%'): QueryBuilder
    {
        return $this->searchLikeOrNot($qb->expr()->like(...), $qb, $alias, $field, $value, $paramPrefix, $composite, $format);
    }

    public function searchJson(QueryBuilder $qb, string $alias, string $field, string $value, string $paramPrefix = 'searchJson_', ?Composite $composite = null): QueryBuilder
    {
        return $this->searchLike($qb, $alias, $field, $value, $paramPrefix, $composite, '%%"%s"%%');
    }

    public function searchJsonArray(QueryBuilder $qb, string $alias, string $field, array $values, bool $all = false): QueryBuilder
    {
        $composite = $all ? $qb->expr()->andX() : $qb->expr()->orX();

        foreach ($values as $index => $value) {
            $this->searchJson($qb, $alias, $field, $value, 'searchJson' . $index . '_', $composite);
        }

        if ($composite->count() > 0) {
            $qb->andWhere($composite);
        }

        return $qb;
    }

    /**
     * @param array|Composite[] $condGroups
     */
    public function addCondGroup(QueryBuilder $qb, array $condGroups, bool $all = false): QueryBuilder
    {
        $composite = $all ? $qb->expr()->andX() : $qb->expr()->orX();

        foreach ($condGroups as $condGroup) {
            if ($condGroup->count() > 0) {
                $composite->add($condGroup);
            }
        }

        if ($composite->count() > 0) {
            $qb->andWhere($composite);
        }

        return $qb;
    }

    public function addSearchFields(QueryBuilder $qb, array $fields, array $words, string $prefix = 'search', string $format = '%%%s%%', string $method = 'like', bool $allWords = true, bool $allFields = false): QueryBuilder
    {
        $composite = $this->getSearchFieldsConditions($qb, $fields, $words, $prefix, $format, $method, $allWords, $allFields);

        if ($composite->count() > 0) {
            $qb->andWhere($composite);
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

        if (array_any($joins, fn ($join) => ($join->getJoin() === $joinColumn) && ($join->getAlias() === $joinAlias))) {
            return $qb;
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
