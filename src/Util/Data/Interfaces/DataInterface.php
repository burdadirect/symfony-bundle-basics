<?php

namespace HBM\BasicsBundle\Util\Data\Interfaces;

interface DataInterface {
  /**
   * @param string|null $filter
   *
   * @return array
   */
  public static function keys(string $filter = NULL): array;

  /**
   * @param string $key
   * @param string|null $filter
   * @param string|null $default
   *
   * @return string|null
   */
  public static function key(string $key, ?string $filter = NULL, ?string $default = NULL): ?string;

  /**
   * @param string|null $filter
   * @param array|null $keys
   * @param string|null $sort
   *
   * @return array
   */
  public static function data(?string $filter = NULL, ?array $keys = NULL, string $sort = NULL): array;

  /**
   * @return array
   */
  public static function _data(): array;

  /**
   * @param string|null $filter
   * @param array|null $keys
   * @param string|null $sort
   *
   * @return array
   */
  public static function filter(?string $filter = NULL, ?array $keys = NULL, string $sort = NULL): array;

  /**
   * @param string|null $field
   * @param string|array|mixed|null $default
   * @param string|null $filter
   * @param array|null $keys
   * @param string|null $prefix
   *
   * @return array
   */
  public static function flatten(string $field = NULL, $default = NULL, string $filter = NULL, array $keys = NULL, string $prefix = NULL): array;

  /**
   * @param string|null $key
   *
   * @return array|null
   */
  public static function get(string $key = NULL): ?array;

  /**
   * @param string $key
   * @param string $format
   * @param string|null $default
   * @param array|null $fields
   *
   * @return string|null
   */
  public static function format(string $key, string $format, string $default = NULL, ?array $fields = NULL): ?string;

  /**
   * @param string $key
   * @param string $format
   * @param string|null $default
   * @param array|null $fields
   *
   * @return string|null
   */
  public static function formatWithKey(string $key, string $format, string $default = NULL, ?array $fields = NULL): ?string;

  /**
   * @param string $key
   * @param callable $callback
   * @param string|null $default
   * @param array|null $fields
   *
   * @return string|null
   */
  public static function formatCallback(string $key, callable $callback, string $default = NULL, ?array $fields = NULL): ?string;

  /**
   * @param string|null $key
   * @param mixed|null $default
   * @param string|null $field
   *
   * @return string|null
   */
  public static function label(string $key = NULL, $default = NULL, string $field = NULL): ?string;

  /**
   * @param string|null $key
   * @param string|null $field
   * @param mixed|null $default
   *
   * @return mixed|string|null
   */
  public static function field(string $key = NULL, string $field = NULL, $default = NULL);

  /**
   * @param string|null $key
   * @param array|null $fields
   * @param mixed|null $default
   *
   * @return mixed|string|null
   */
  public static function fields(string $key = NULL, array $fields = [], $default = NULL);

  /**
   * @return false|int|string
   */
  public static function random();

  /**
   * @param string|null $filter
   *
   * @return int
   */
  public static function count(string $filter = NULL): int;
}
