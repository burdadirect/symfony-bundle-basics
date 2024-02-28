<?php

namespace HBM\BasicsBundle\Util\Enum\Traits;

/**
 * @method static array cases()
 */
trait EnumTrait
{
    abstract public function fields(): array;

    public function fieldsFiltered(array $fields = null, callable $callback = null, bool $withCaseValue = false, bool $withCaseName = false): array
    {
        $data = $this->fields();

        if ($fields !== null) {
            $fieldsToUse = array_fill_keys($fields, true);
            // Only use the reduced set of fields.
            $dataFiltered = array_intersect_key($data, $fieldsToUse);
            // Bring the reduced set in the appropriate order.
            $data = array_replace($fieldsToUse, $dataFiltered);
        }

        if ($withCaseName) {
            array_unshift($data, $this->name);
        }
        if ($withCaseValue) {
            array_unshift($data, $this->value);
        }

        if (is_callable($callback)) {
             return $callback(...array_values($data));
        }

        return $data;
    }

    public function field(string $field, mixed $default = null): mixed
    {
        return $this->fields()[$field] ?? $default;
    }

    public function filter(): array
    {
        return $this->field('filter', []);
    }

    public function format(string $format, array $fields = null, callable $callback = null, bool $withCaseValue = false, bool $withCaseName = false): string
    {
        return sprintf($format, ...array_values($this->fieldsFiltered($fields, $callback, $withCaseName, $withCaseValue)));
    }

    /**
     * @return array<array{
     *    case: self,
     *    name: string,
     *    value: string|int,
     *    data: array
     *  }>
     */
    public static function casesData(): array
    {
        $casesData = [];
        foreach (self::cases() as $case) {
            $casesData[$case->value] = [
              'case'  => $case,
              'name'  => $case->name,
              'value' => $case->value,
              'fields'  => $case->fields(),
            ];
        }
        return $casesData;
    }

    /**
     * @return array<string, self>
     */
    public static function casesFiltered(string $filter = null, array $cases = null, string $sortByField = null): array
    {
        $result = [];

        foreach (self::casesData() as $caseWithData) {
            // Filter by string.
            $excludeByFilter = is_string($filter) && !in_array($filter, $caseWithData['case']->filter(), true);

            // Filter by cases.
            $excludeByCase = is_array($cases) && !in_array($caseWithData['case'], $cases, true);

            if (!$excludeByFilter && !$excludeByCase) {
                $result[] = $caseWithData;
            }
        }

        if ($sortByField) {
            uasort($result, static function ($a, $b) use ($sortByField) {
                $valA = $a['fields'][$sortByField] ?? null;
                $valB = $b['fields'][$sortByField] ?? null;

                if ($valA === $valB) {
                    return 0;
                }

                return ($valA < $valB) ? -1 : 1;
            });
        }

        return array_column($result, 'case', 'value');
    }

    public static function casesFlat(string $field = null, mixed $default = null, string $filter = null, array $cases = null, string $sortByField = null, string $prefix = null, string $postfix = null): array
    {
        $array = [];
        foreach (self::casesFiltered($filter, $cases, $sortByField) as $case) {
            $array[$prefix.$case->value.$postfix] = $case->field($field, $default);
        }

        return $array;
    }

    public static function random(string $filter = null): ?self
    {
        $cases = self::casesFiltered($filter);
        shuffle($cases);
        return reset($cases) ?: null;
    }

    public static function count(string $filter = null): int
    {
        return count(self::casesFiltered($filter));
    }
}
