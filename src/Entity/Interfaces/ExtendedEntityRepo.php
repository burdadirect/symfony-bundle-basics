<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;

interface ExtendedEntityRepo extends ObjectRepository
{
    public function createQueryBuilderForAlias(string $alias): QueryBuilder;

    public function findRandomBy(array $criteria, ?int $limit = null): array;

    public function addSortations(QueryBuilder $qb, array $sortations, array $default = []): QueryBuilder;

    public function searchValue(QueryBuilder $qb, string $alias, string $field, mixed $value = null, string $prefix = 'value'): QueryBuilder;

    public function searchChoices(QueryBuilder $qb, string $alias, string $field, ?array $choices = null, string $prefix = 'choices'): QueryBuilder;

    public function searchSelection(QueryBuilder $qb, string $alias, string $field, ?array $selections = null, string $prefix = 'selections'): QueryBuilder;

    public function searchNull(QueryBuilder $qb, string $alias, string $field, ?bool $isNull = null): QueryBuilder;

    public function addSearchFields(QueryBuilder $qb, array $fields, array $words, string $prefix = 'search', string $format = '%%%s%%', string $method = 'like'): QueryBuilder;

    public function leftJoinOnce(QueryBuilder $qb, string $alias, string $field, string $joinAlias): QueryBuilder;
}
