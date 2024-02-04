<?php

namespace HBM\BasicsBundle\Util\Enum\Interfaces;

interface EnumInterface
{
    public function fields(): array;

    public function fieldsFiltered(array $fields = null, callable $callback = null, bool $withCaseName = false, bool $withCaseValue = false): array;

    public function field(string $field, mixed $default = null): mixed;

    public function filter(): array;

    public function format(string $format, array $fields = null, callable $callback = null, bool $withCaseValue = false, bool $withCaseName = false): string;

    /**
     * @return array<array{
     *    case: self,
     *    name: string,
     *    value: string|int,
     *    data: array
     *  }>
     */
    public static function casesData(): array;

    /**
     * @return array<string, self>
     */
    public static function casesFiltered(string $filter = null, array $cases = null, string $sortByField = null): array;

    public static function casesFlat(string $field = null, mixed $default = null, string $filter = null, array $cases = null, string $sortByField = null, string $prefix = null, string $postfix = null): array;

    public static function random(string $filter = null): ?self;

    public static function count(string $filter = null): int;
}
