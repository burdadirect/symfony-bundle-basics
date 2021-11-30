<?php

namespace HBM\BasicsBundle\Util\Data;

abstract class AbstractData {

  protected static string $filter = 'filter';
  protected static string $label = 'text';

  private static array $data = [];

  public static function keys(string $filter = NULL) : array {
    return array_keys(static::filter($filter));
  }

  public static function key(string $key, string $filter = NULL, string $default = NULL) : ?string {
    $keys = static::keys($filter);

    return in_array($key, $keys, true) ? $key : $default;
  }

  public static function data(string $filter = NULL, array $keys = NULL) : array {
    return static::filter($filter, $keys);
  }

  public static function _data() : array {
    return static::$data;
  }

  public static function filter(string $filter = NULL, array $keys = NULL) : array {
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
      return $filtered;
    }

    if ($keys !== NULL) {
      return array_intersect_key(static::_data(), array_fill_keys($keys, TRUE));
    }

    return static::_data();
  }

  protected static function filterCallback($value) {
    return $value;
  }

  /****************************************************************************/

  public static function flatten(string $field = NULL, string $default = NULL, string $filter = NULL, array $keys = NULL, string $prefix = NULL) : array {
    $array = [];
    foreach (static::filter($filter, $keys) as $key => $value) {
      $array[$prefix.$key] = $value[$field ?: static::$label] ?? $default;
    }

    return $array;
  }

  public static function get(string $key = NULL) : ?array {
    return static::_data()[$key] ?? NULL;
  }

  /****************************************************************************/

  public static function format(string $key, string $format, string $default = NULL) : ?string {
    if ($data = static::_data()[$key] ?? NULL) {
      return sprintf($format, ...array_values($data));
    }
    return $default;
  }

  public static function formatWithKey(string $key, string $format, string $default = NULL) : ?string {
    if ($data = static::_data()[$key] ?? NULL) {
      return sprintf($format, $key, ...array_values($data));
    }
    return $default;
  }

  public static function formatCallback(string $key, callable $callback, string $default = NULL) : ?string {
    if ($data = static::_data()[$key] ?? NULL) {
      return $callback(...array_values($data));
    }
    return $default;
  }

  /****************************************************************************/

  public static function label(string $key = NULL, string $default = NULL, string $field = NULL) : ?string {
    if (($key !== NULL) && (isset(static::_data()[$key][$field ?: static::$label]))) {
      return static::_data()[$key][$field ?: static::$label];
    }
    return $default;
  }

  /****************************************************************************/

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
