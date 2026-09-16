<?php

namespace HBM\BasicsBundle\Util\Enum\Model;

/**
 * @template BE of \BackedEnum
 */
class EnumArray {

    /** @var class-string<BE> */
    private string $enumClass;

    /** @var array<array-key, BE> */
    private array $enumCases;

    /**
     * @param class-string<BE> $enumClass
     * @param array<array-key, BE> $enumCases
     */
    public function __construct(string $enumClass, array $enumCases = []) {
        $this->enumClass = $enumClass;
        $this->enumCases = array_values($enumCases);
    }

    /**
     * @return array<array-key, BE>
     */
    public function toArray(): array {
        return $this->enumCases;
    }

    /**
     * @return list<int|string>
     */
    public function values(?string $filter = null, ?string $sortByField = null): array {
        return $this->enumClass::casesFiltered($filter, $this->enumCases, $sortByField, null, 'value');
    }

    /**
     * @return list<string>
     */
    public function names(?string $filter = null, ?string $sortByField = null): array {
        return $this->enumClass::casesFiltered($filter, $this->enumCases, $sortByField, null, 'name');
    }

    /**
     * @param BE $case
     */
    public function contains(\BackedEnum $case): bool {
        return in_array($case, $this->enumCases, true);
    }

    public function filter(?string $filter = null, ?string $sortByField = null, ?string $arrayKey = 'value', ?string $arrayValue = 'case'): array {
        return $this->enumClass::casesFiltered($filter, $this->enumCases, $sortByField, $arrayKey, $arrayValue);
    }

    public function flat(string|callable|null $field = null, mixed $default = null, ?string $filter = null, ?string $sortByField = null, ?string $formatKey = '%1$s', ?string $method = null): array {
        return $this->enumClass::casesFlat($field, $default, $filter, $this->enumCases, $sortByField, $formatKey, $method);
    }

    public function count(): int {
        return count($this->enumCases);
    }

    public function from(array $cases): array {
        return array_map(fn(string $case) => $this->enumClass::from($case), $cases);
    }

    public function tryFrom(array $cases): array {
        return array_map(fn(string $case) => $this->enumClass::tryFrom($case), $cases);
    }
}
