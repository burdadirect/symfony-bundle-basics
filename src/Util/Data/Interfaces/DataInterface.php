<?php

namespace HBM\BasicsBundle\Util\Data\Interfaces;

interface DataInterface
{
    public static function keys(string $filter = null): array;

    public static function key(string $key, string $filter = null, string $default = null): ?string;

    public static function data(string $filter = null, array $keys = null, string $sort = null): array;

    public static function _data(): array;

    public static function filter(string $filter = null, array $keys = null, string $sort = null): array;

    /**
     * @param null|array|mixed|string $default
     */
    public static function flatten(string $field = null, $default = null, string $filter = null, array $keys = null, string $prefix = null): array;

    public static function get(string $key = null): ?array;

    public static function format(string $key, string $format, string $default = null, array $fields = null): ?string;

    public static function formatWithKey(string $key, string $format, string $default = null, array $fields = null): ?string;

    public static function formatCallback(string $key, callable $callback, string $default = null, array $fields = null): ?string;

    /**
     * @param null|mixed $default
     */
    public static function label(string $key = null, $default = null, string $field = null): ?string;

    /**
     * @param null|mixed $default
     *
     * @return null|mixed|string
     */
    public static function field(string $key = null, string $field = null, $default = null);

    /**
     * @param null|array $fields
     * @param null|mixed $default
     *
     * @return null|mixed|string
     */
    public static function fields(string $key = null, array $fields = [], $default = null);

    /**
     * @return false|int|string
     */
    public static function random();

    public static function count(string $filter = null): int;
}
