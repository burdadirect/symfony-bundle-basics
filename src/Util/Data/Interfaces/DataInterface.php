<?php

namespace HBM\BasicsBundle\Util\Data\Interfaces;

interface DataInterface
{
    public static function keys(?string $filter = null): array;

    public static function key(string $key, ?string $filter = null, ?string $default = null): ?string;

    public static function data(?string $filter = null, ?array $keys = null, ?string $sort = null): array;

    public static function _data(): array;

    public static function filter(?string $filter = null, ?array $keys = null, ?string $sort = null): array;

    public static function flatten(?string $field = null, mixed $default = null, ?string $filter = null, ?array $keys = null, ?string $prefix = null): array;

    public static function get(?string $key = null): ?array;

    public static function format(string $key, string $format, ?string $default = null, ?array $fields = null): ?string;

    public static function formatWithKey(string $key, string $format, ?string $default = null, ?array $fields = null): ?string;

    public static function formatCallback(string $key, callable $callback, ?string $default = null, ?array $fields = null): ?string;

    public static function label(?string $key = null, mixed $default = null, ?string $field = null): ?string;

    public static function field(?string $key = null, ?string $field = null, mixed $default = null): mixed;

    public static function fields(?string $key = null, array $fields = [], mixed $default = null): mixed;

    public static function random(): false|int|string;

    public static function count(?string $filter = null): int;
}
