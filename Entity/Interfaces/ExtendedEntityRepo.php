<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;

interface ExtendedEntityRepo extends ObjectRepository {

  /**
   * Creates a new QueryBuilder instance that is prepopulated for this entity name.
   *
   * @param string $alias
   * @param string $indexBy The index for the from.
   *
   * @return QueryBuilder
   */
  public function createQueryBuilder($alias, $indexBy = null);

  /**
   * Finds entities by a set of criteria.
   *
   * @param array      $criteria
   * @param int|null   $limit
   *
   * @return array The objects.
   */
  public function findRandomBy(array $criteria, $limit = null) : array;

  /**
   * @param QueryBuilder $qb
   * @param array $sortations
   * @param array $default
   *
   * @return QueryBuilder
   */
  public function addSortations(QueryBuilder $qb, array $sortations, array $default = []) : QueryBuilder;

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
  public function searchValue(QueryBuilder $qb, string $alias, string $field, $value = NULL, string $prefix = 'value') : QueryBuilder;

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
  public function searchChoices(QueryBuilder $qb, string $alias, string $field, array $choices = NULL, string $prefix = 'choices'): QueryBuilder;

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
  public function searchSelection(QueryBuilder $qb, string $alias, string $field, array $selections = NULL, string $prefix = 'selections'): QueryBuilder;

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
  public function searchNull(QueryBuilder $qb, string $alias, string $field, bool $isNull = NULL) : QueryBuilder;

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
  public function addSearchFields(QueryBuilder $qb, array $fields, array $words, string $prefix = 'search', string $format = '%%%s%%', string $method = 'like') : QueryBuilder;

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
  public function leftJoinOnce(QueryBuilder $qb, string $alias, string $field, string $joinAlias) : QueryBuilder;

}
