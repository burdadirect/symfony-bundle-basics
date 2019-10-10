<?php

namespace HBM\BasicsBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractServiceEntityRepo extends ServiceEntityRepository {

  /**
   * Finds entities by a set of criteria.
   *
   * @param array      $criteria
   * @param int|null   $limit
   *
   * @return array The objects.
   */
  public function findRandomBy(array $criteria, $limit = null) : array {
    try {
      $randomOffset = random_int(0, max(0, $this->count($criteria) - 1));
    } catch (\Exception $e) {
      $randomOffset = 0;
    }

    return $this->findBy($criteria, [], $limit, $randomOffset);
  }

  /**
   * @param QueryBuilder $qb
   * @param array $sortations
   * @param array $default
   *
   * @return QueryBuilder
   */
  public function addSortations(QueryBuilder $qb, array $sortations, array $default = []) : QueryBuilder {
    if (\count($sortations) === 0) {
      $sortations = $default;
    }

    foreach ($sortations as $key => $value) {
      $qb->addOrderBy($key, $value);
    }

    return $qb;
  }

  /**
   * Adjust a query builder for searching.
   *
   * @param QueryBuilder $qb
   * @param string $alias
   * @param string $field
   * @param string|int|mixed|null $value
   * @param string $prefix
   *
   * @return QueryBuilder
   */
  public function searchValue(QueryBuilder $qb, string $alias, string $field, $value = NULL, string $prefix = 'value') : QueryBuilder {
    if ($value !== NULL) {
      $qb->andWhere($qb->expr()->eq($alias.'.'.$field, ':'.$prefix))->setParameter($prefix, $value);
    }

    return $qb;
  }

  /**
   * Adjust a query builder for searching.
   *
   * @param QueryBuilder $qb
   * @param string $alias
   * @param string $field
   * @param array|null $choices
   * @param string $prefix
   *
   * @return QueryBuilder
   */
  public function searchChoices(QueryBuilder $qb, string $alias, string $field, array $choices = NULL, string $prefix = 'choices'): QueryBuilder {
    if (count($choices) > 0) {
      $qb->andWhere($qb->expr()->in($alias.'.'.$field, ':'.$prefix))->setParameter($prefix, $choices);
    }

    return $qb;
  }

  /**
   * Adjust a query builder for searching.
   *
   * @param QueryBuilder $qb
   * @param string $alias
   * @param string $field
   * @param array|null $selections
   * @param string $prefix
   *
   * @return QueryBuilder
   */
  public function searchSelection(QueryBuilder $qb, string $alias, string $field, array $selections = NULL, string $prefix = 'selections'): QueryBuilder {
    if (count($selections) > 0) {
      $qb->andWhere($qb->expr()->in($alias.'.'.$field, ':'.$prefix))->setParameter($prefix, $selections);
    } else {
      $qb->andWhere('1 = 0');
    }

    return $qb;
  }

  /**
   * Adjust a query builder for searching.
   *
   * @param QueryBuilder $qb
   * @param string $alias
   * @param string $field
   * @param bool|null $isNull
   *
   * @return QueryBuilder
   */
  public function searchNull(QueryBuilder $qb, string $alias, string $field, bool $isNull = NULL) : QueryBuilder {
    if ($isNull === TRUE) {
      $qb->andWhere($qb->expr()->isNull($alias.'.'.$field));
    } elseif ($isNull === FALSE) {
      $qb->andWhere($qb->expr()->isNotNull($alias.'.'.$field));
    }

    return $qb;
  }

  /**
   * Add search field to querybuilder.
   *
   * @param QueryBuilder $qb
   * @param array $fields
   * @param array $words
   * @param string $prefix
   * @param string $format
   * @param string $method
   *
   * @return QueryBuilder
   */
  public function addSearchFields(QueryBuilder $qb, array $fields, array $words, string $prefix = 'search', string $format = '%%%s%%', string $method = 'like') : QueryBuilder {
    $and = $qb->expr()->andX();

    $counter = 0;
    foreach ($words as $word) {
      $or = $qb->expr()->orX();

      foreach ($fields as $field) {
        if ($method === 'eq') {
          $or->add($qb->expr()->eq($field, ':'.$prefix.$counter));
        } else {
          $or->add($qb->expr()->like($field, ':'.$prefix.$counter));
        }
      }

      $qb->setParameter($prefix.$counter, sprintf($format, $word));
      $counter++;

      $and->add($or);
    }

    if ($and->count() > 0) {
      $qb->andWhere($and);
    }

    return $qb;
  }

  /**
   * Check if querybuilder already has been joined.
   *
   * @param QueryBuilder $qb
   * @param string $alias
   * @param string $field
   * @param string $joinAlias
   *
   * @return QueryBuilder
   */
  public function leftJoinOnce(QueryBuilder $qb, string $alias, string $field, string $joinAlias) : QueryBuilder {
    /** @var Join[] $joins */
    $joins = $qb->getDQLPart('join')[$alias] ?? [];

    $joinColumn = $alias.'.'.$field;
    foreach ($joins as $join) {
      if (($join->getJoin() === $joinColumn) && ($join->getAlias() === $joinAlias)) {
        return $qb;
      }
    }

    $qb->leftJoin($joinColumn, $joinAlias);

    return $qb;
  }

}
