<?php

namespace HBM\BasicsBundle\Util\Data;

abstract class AbstractData {

  protected static $label = 'text';

  private static $data = [];

  public static function keys(string $filter = NULL) : array {
    return array_keys(static::filter($filter));
  }

  public static function data(string $filter = NULL, array $keys = NULL) : array {
    return static::filter($filter, $keys);
  }

  public static function filter(string $filter = NULL, array $keys = NULL) : array {
    if ($filter !== NULL) {
      $filtered = [];
      foreach (static::$data as $key => $value) {
        if (isset($value['filter']) && is_array($value['filter']) && in_array($filter, $value['filter'], TRUE)) {
          $filtered[$key] = $value;
        }
      }
      return $filtered;
    }

    if ($keys !== NULL) {
      return array_intersect_key(static::$data, array_fill_keys($keys, TRUE));
    }

    return static::$data;
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
    return static::$data[$key] ?? NULL;
  }

  /****************************************************************************/

  public static function format(string $key, string $format, string $default = NULL) : ?string {
    if ($data = static::$data[$key] ?? NULL) {
      return sprintf($format, ...array_values($data));
    }
    return $default;
  }

  public static function formatWithKey(string $key, string $format, string $default = NULL) : ?string {
    if ($data = static::$data[$key] ?? NULL) {
      return sprintf($format, $key, ...array_values($data));
    }
    return $default;
  }

  public static function formatCallback(string $key, callable $callback, string $default = NULL) : ?string {
    if ($data = static::$data[$key] ?? NULL) {
      return $callback(...array_values($data));
    }
    return $default;
  }

  /****************************************************************************/

  public static function label(string $key, string $default = NULL, string $field = NULL) : ?string {
    return static::$data[$key][$field ?: static::$label] ?? $default;
  }

  /****************************************************************************/

  public static function random() {
    $keys = array_keys(static::$data);
    shuffle($keys);
    return reset($keys);
  }

}
