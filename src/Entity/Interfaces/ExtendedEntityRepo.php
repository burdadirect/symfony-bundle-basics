<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;

interface ExtendedEntityRepo extends ObjectRepository
{
    /**
     * Creates a new QueryBuilder instance that is prepopulated for this entity name.
     */
    public function createQueryBuilderForAlias(string $alias): QueryBuilder;

    /**
     * Finds entities by a set of criteria.
     *
     * @return array the objects
     */
    public function findRandomBy(array $criteria, ?int $limit = null): array;

    public function addSortations(QueryBuilder $qb, array $sortations, array $default = []): QueryBuilder;

    /**
     * Adjust a query builder for searching.
     */
    public function searchValue(QueryBuilder $qb, string $alias, string $field, mixed $value = null, string $prefix = 'value'): QueryBuilder;

    /**
     * Adjust a query builder for searching.
     */
    public function searchChoices(QueryBuilder $qb, string $alias, string $field, ?array $choices = null, string $prefix = 'choices'): QueryBuilder;

    /**
     * Adjust a query builder for searching.
     */
    public function searchSelection(QueryBuilder $qb, string $alias, string $field, ?array $selections = null, string $prefix = 'selections'): QueryBuilder;

    /**
     * Adjust a query builder for searching.
     */
    public function searchNull(QueryBuilder $qb, string $alias, string $field, ?bool $isNull = null): QueryBuilder;

    /**
     * Add search field to querybuilder.
     */
    public function addSearchFields(QueryBuilder $qb, array $fields, array $words, string $prefix = 'search', string $format = '%%%s%%', string $method = 'like'): QueryBuilder;

    /**
     * Check if querybuilder already has been joined.
     */
    public function leftJoinOnce(QueryBuilder $qb, string $alias, string $field, string $joinAlias): QueryBuilder;
}
