<?php

namespace HBM\BasicsBundle\Util\Enum\Interfaces;

interface EnumInterface
{
    public function fields(): array;

    public function fieldsFiltered(?array $fields = null, ?callable $callback = null, bool $withCaseName = false, bool $withCaseValue = false): array;

    public function field(string $field, mixed $default = null): mixed;

    public function filter(): array;

    public function format(string $format, ?array $fields = null, ?callable $callback = null, bool $withCaseValue = false, bool $withCaseName = false): string;

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
    public static function casesFiltered(?string $filter = null, ?array $cases = null, ?string $sortByField = null, ?string $arrayKey = 'value', ?string $arrayValue = 'case'): array;

    public static function casesFlat(string|callable|null $field = null, mixed $default = null, ?string $filter = null, ?array $cases = null, ?string $sortByField = null, ?string $formatKey = '%1$s',?string $method = null): array;

    public static function random(?string $filter = null, ?array $cases = null): ?self;

    public static function count(?string $filter = null): int;
}
