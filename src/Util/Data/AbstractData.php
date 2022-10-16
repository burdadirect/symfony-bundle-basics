<?php

namespace HBM\BasicsBundle\Util\Data;

abstract class AbstractData {

  protected static string $filter = 'filter';
  protected static string $label = 'text';

  protected static bool $filterAndKeys = false;

  private static array $data = [];

  /**
   * @param string|null $filter
   *
   * @return array
   */
  public static function keys(string $filter = NULL) : array {
    return array_keys(static::filter($filter));
  }

  /**
   * @param string $key
   * @param string|null $filter
   * @param string|null $default
   *
   * @return string|null
   */
  public static function key(string $key, ?string $filter = NULL, ?string $default = NULL) : ?string {
    $keys = static::keys($filter);

    return in_array($key, $keys, true) ? $key : $default;
  }

  /**
   * @param string|null $filter
   * @param array|null $keys
   * @param string|null $sort
   *
   * @return array
   */
  public static function data(?string $filter = NULL, ?array $keys = NULL, string $sort = NULL) : array {
    return static::filter($filter, $keys, $sort);
  }

  /**
   * @return array
   */
  public static function _data() : array {
    return static::$data;
  }

  /**
   * @param string|null $filter
   * @param array|null $keys
   * @param string|null $sort
   *
   * @return array
   */
  public static function filter(?string $filter = NULL, ?array $keys = NULL, string $sort = NULL) : array {
    $dataToUse = static::_data();

    if ($filter !== NULL) {
      $filtered = [];
      foreach (static::_data() as $key => $value) {
        if (isset($value[static::$filter]) && is_array($value[static::$filter])) {
          $valuesToFilter = array_map([static::class, 'filterCallback'], $value[static::$filter]);
          if (in_array($filter, $valuesToFilter, TRUE)) {
            $filtered[$key] = $value;
          }
        }
      }
      $dataToUse = $filtered;
    }

    if ((static::$filterAndKeys || ($filter === null)) && ($keys !== NULL)) {
      $dataToUse = array_intersect_key($dataToUse, array_fill_keys($keys, TRUE));
    }

    if ($sort) {
      uasort($dataToUse, static function ($a, $b) use ($sort) {
        $valA = $a[$sort] ?? null;
        $valB = $b[$sort] ?? null;
        if ($valA === $valB) {
          return 0;
        }
        return ($valA < $valB) ? -1 : 1;
      });
    }

    return $dataToUse;
  }

  /**
   * @param $value
   *
   * @return mixed
   */
  protected static function filterCallback($value) {
    return $value;
  }

  /****************************************************************************/

  /**
   * @param string|null $field
   * @param string|array|mixed|null $default
   * @param string|null $filter
   * @param array|null $keys
   * @param string|null $prefix
   *
   * @return array
   */
  public static function flatten(string $field = NULL, $default = NULL, string $filter = NULL, array $keys = NULL, string $prefix = NULL) : array {
    $array = [];
    foreach (static::filter($filter, $keys) as $key => $value) {
      $array[$prefix.$key] = $value[$field ?: static::$label] ?? $default;
    }

    return $array;
  }

  /**
   * @param string|null $key
   *
   * @return array|null
   */
  public static function get(string $key = NULL) : ?array {
    return static::_data()[$key] ?? NULL;
  }

  /****************************************************************************/

  /**
   * @param string $key
   * @param string $format
   * @param string|null $default
   * @param array|null $fields
   *
   * @return string|null
   */
  public static function format(string $key, string $format, string $default = NULL, ?array $fields = null) : ?string {
    if ($data = static::_data()[$key] ?? NULL) {
      $values = $fields ? self::fields($key, $fields) : $data;
      return sprintf($format, ...array_values($values));
    }
    return $default;
  }

  /**
   * @param string $key
   * @param string $format
   * @param string|null $default
   * @param array|null $fields
   *
   * @return string|null
   */
  public static function formatWithKey(string $key, string $format, string $default = NULL, ?array $fields = null) : ?string {
    if ($data = static::_data()[$key] ?? NULL) {
      $values = $fields ? self::fields($key, $fields) : $data;
      return sprintf($format, $key, ...array_values($values));
    }
    return $default;
  }

  /**
   * @param string $key
   * @param callable $callback
   * @param string|null $default
   * @param array|null $fields
   *
   * @return string|null
   */
  public static function formatCallback(string $key, callable $callback, string $default = NULL, ?array $fields = null) : ?string {
    if ($data = static::_data()[$key] ?? NULL) {
      $values = $fields ? self::fields($key, $fields) : $data;
      return $callback(...array_values($values));
    }
    return $default;
  }

  /****************************************************************************/

  /**
   * @param string|null $key
   * @param mixed|null $default
   * @param string|null $field
   *
   * @return string|null
   */
  public static function label(string $key = NULL, $default = NULL, string $field = NULL) : ?string {
    if (($key !== NULL) && (isset(static::_data()[$key][$field ?: static::$label]))) {
      return static::_data()[$key][$field ?: static::$label];
    }
    return $default;
  }

  /**
   * @param string|null $key
   * @param string|null $field
   * @param mixed|null $default
   *
   * @return mixed|string|null
   */
  public static function field(string $key = NULL, string $field = NULL, $default = NULL) {
    if (($key !== NULL) && (isset(static::_data()[$key][$field ?: static::$label]))) {
      return static::_data()[$key][$field ?: static::$label];
    }
    return $default;
  }

  /**
   * @param string|null $key
   * @param array|null $fields
   * @param mixed|null $default
   *
   * @return mixed|string|null
   */
  public static function fields(string $key = NULL, array $fields = [], $default = NULL) {
    if (($key !== NULL) && (isset(static::_data()[$key]))) {
      $fieldsAll = static::_data()[$key];
      $fieldsFiltered = [];
      foreach ($fields as $field) {
        $fieldsFiltered[$field] = $fieldsAll[$field] ?? $default;
      }
      return $fieldsFiltered;
    }
    return $default;
  }

  /****************************************************************************/

  /**
   * @return false|int|string
   */
  public static function random() {
    $keys = array_keys(static::_data());
    shuffle($keys);
    return reset($keys);
  }

  /**
   * @param string|null $filter
   *
   * @return int
   */
  public static function count(string $filter = NULL) : int {
    return count(self::keys($filter));
  }

}
