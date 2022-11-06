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
 * @template-extends ServiceEntityRepository<T>
 */
abstract class AbstractServiceEntityRepo extends ServiceEntityRepository implements ExtendedEntityRepo {

  public function updateFields(AbstractEntity $entity, array $fields) {
    $qb = $this->createQueryBuilder('x');
    $qb->update();
    foreach ($fields as $fieldKey => $fielValue) {
      $qb->set('x.'.$fieldKey, ':'.$fieldKey)->setParameter($fieldKey, $fielValue);
    }
    $qb->where($qb->expr()->eq('x.id', ':id'))->setParameter('id', $entity->getId());

    return $qb->getQuery()->execute();
  }

  /**
   * @inheritDoc
   */
  public function findRandomBy(array $criteria, $limit = null) : array {
    try {
      $limitCap = $limit ?? 0;
      $randomOffset = random_int(0, max(0, $this->count($criteria) - $limitCap));
    } catch (\Exception $e) {
      $randomOffset = 0;
    }

    return $this->findBy($criteria, [], $limit, $randomOffset);
  }

  /**
   * @inheritDoc
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
   * @inheritDoc
   */
  public function searchValue(QueryBuilder $qb, string $alias, string $field, $value = NULL, string $prefix = 'value') : QueryBuilder {
    if ($value !== NULL) {
      $qb->andWhere($qb->expr()->eq($alias.'.'.$field, ':'.$prefix))->setParameter($prefix, $value);
    }

    return $qb;
  }

  /**
   * @inheritDoc
   */
  public function searchChoices(QueryBuilder $qb, string $alias, string $field, array $choices = NULL, string $prefix = 'choices'): QueryBuilder {
    if (count($choices) > 0) {
      $qb->andWhere($qb->expr()->in($alias.'.'.$field, ':'.$prefix))->setParameter($prefix, $choices);
    }

    return $qb;
  }

  /**
   * @param QueryBuilder $qb
   * @param string $alias
   * @param string $field
   * @param string $joinAlias
   * @param array|NULL $relations
   * @param string $prefix
   *
   * @return QueryBuilder
   */
  public function searchManyToMany(QueryBuilder $qb, string $alias, string $field, string $joinAlias, array $relations = NULL, string $prefix = 'relations'): QueryBuilder {
    if (count($relations) > 0) {
      $this->leftJoinOnce($qb, $alias, $field, $joinAlias);
      $qb->andWhere($qb->expr()->in($joinAlias, ':'.$prefix))->setParameter($prefix, $relations);
    }

    return $qb;
  }

  /**
   * @param QueryBuilder $qb
   * @param string $alias
   * @param string $field
   * @param string $joinAlias
   * @param bool|NULL $isNull
   *
   * @return QueryBuilder
   */
  public function searchManyToManyNull(QueryBuilder $qb, string $alias, string $field, string $joinAlias, bool $isNull = null): QueryBuilder {
    if ($isNull === TRUE) {
      $this->leftJoinOnce($qb, $alias, $field, $joinAlias);
      $qb->andWhere($qb->expr()->isNull($joinAlias));
    } elseif ($isNull === FALSE) {
      $qb->andWhere($qb->expr()->isNotNull($joinAlias));
    }

    return $qb;
  }

  /**
   * @inheritDoc
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
   * @inheritDoc
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
   * @param QueryBuilder $qb
   * @param array|Composite[] $condGroups
   * @param bool $all
   *
   * @return QueryBuilder
   */
  public function addCondGroup(QueryBuilder $qb, array $condGroups, bool $all = FALSE) : QueryBuilder {
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
   * @inheritDoc
   */
  public function addSearchFields(QueryBuilder $qb, array $fields, array $words, string $prefix = 'search', string $format = '%%%s%%', string $method = 'like', bool $allWords = TRUE, bool $allFields = FALSE) : QueryBuilder {
    $condWords = $this->getSearchFieldsConditions($qb, $fields, $words, $prefix, $format, $method, $allWords, $allFields);

    if ($condWords->count() > 0) {
      $qb->andWhere($condWords);
    }

    return $qb;
  }

  /**
   * @param QueryBuilder $qb
   * @param array $fields
   * @param array $words
   * @param string $prefix
   * @param string $format
   * @param string $method
   * @param bool $allWords
   * @param bool $allFields
   *
   * @return Composite
   */
  public function getSearchFieldsConditions(QueryBuilder $qb, array $fields, array $words, string $prefix = 'search', string $format = '%%%s%%', string $method = 'like', bool $allWords = TRUE, bool $allFields = FALSE) : Composite {
    $condWords = $allWords ? $qb->expr()->andX() : $qb->expr()->orX();

    $counter = 0;
    foreach ($words as $word) {
      $condFields = $allFields ? $qb->expr()->andX() : $qb->expr()->orX();

      foreach ($fields as $field) {
        if ($method === 'eq') {
          $condFields->add($qb->expr()->eq($field, ':'.$prefix.$counter));
        } else {
          $condFields->add($qb->expr()->like($field, ':'.$prefix.$counter));
        }
      }

      $qb->setParameter($prefix.$counter, sprintf($format, $word));
      $counter++;

      $condWords->add($condFields);
    }

    return $condWords;
  }

  /**
   * @inheritDoc
   */
  public function leftJoinOnce(QueryBuilder $qb, string $alias, string $field, string $joinAlias, $conditionType = null, $condition = null, $indexBy = null) : QueryBuilder {
    /** @var Join[] $joins */
    $joins = $qb->getDQLPart('join')[$alias] ?? [];

    $joinColumn = $alias.'.'.$field;
    foreach ($joins as $join) {
      if (($join->getJoin() === $joinColumn) && ($join->getAlias() === $joinAlias)) {
        return $qb;
      }
    }

    $qb->leftJoin($joinColumn, $joinAlias, $conditionType, $condition, $indexBy);

    return $qb;
  }

  /**
   * @param string|callable $callback
   * @param array $criteria
   * @param array $sortation
   *
   * @return array
   */
  public function getIdsAndNamesSorted($callback, array $criteria = [], array $sortation = []) : array {
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
