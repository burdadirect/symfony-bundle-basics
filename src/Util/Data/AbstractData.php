<?php

namespace HBM\BasicsBundle\Util\Data;

use HBM\BasicsBundle\Util\Data\Interfaces\DataInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractData implements DataInterface
{
    private static array $data = [];

    protected static string $filter = 'filter';

    protected static string $label = 'text';

    protected static bool $filterAndKeys = false;

    protected static array $fieldsToTranslate = [];

    protected static bool $translated = false;

    protected static ?TranslatorInterface $translator = null;
    protected static array $translatorParameters = [];
    protected static ?string $translatorDomain = null;
    protected static ?string $translatorLocale = null;

    public static function setTranslator(?TranslatorInterface $translator, array $parameters = [], ?string $domain = null, ?string $locale = null): static {
        self::$translator = $translator;
        self::$translatorParameters = $parameters;
        self::$translatorDomain = $domain;
        self::$translatorLocale = $locale;
        return new static();
    }

    public static function keys(string $filter = null): array
    {
        return array_keys(static::filter($filter));
    }

    public static function key(string|int|bool $key, string $filter = null, string $default = null): ?string
    {
        $keys = static::keys($filter);

        return in_array($key, $keys, true) ? $key : $default;
    }

    public static function data(string $filter = null, array $keys = null, string $sort = null): array
    {
        return static::filter($filter, $keys, $sort);
    }

    public static function _data(): array
    {
        if (static::$translator && !static::$translated && count(static::$fieldsToTranslate) > 0) {
          foreach (static::$data as $dataKey => $dataValue) {
            foreach ($dataValue as $fieldKey => $fieldValue) {
              if (in_array($fieldKey, static::$fieldsToTranslate, true)) {
                static::$data[$dataKey][$fieldKey] = static::$translator->trans($fieldValue, static::$translatorParameters, static::$translatorDomain, static::$translatorLocale);
              }
            }
          }
          static::$translated = true;
        }
        return static::$data;
    }

    public static function filter(string $filter = null, array $keys = null, string $sort = null): array
    {
        $dataToUse = static::_data();

        if ($filter !== null) {
            $filtered = [];
            foreach (static::_data() as $key => $value) {
                if (isset($value[static::$filter]) && is_array($value[static::$filter])) {
                    $valuesToFilter = array_map([static::class, 'filterCallback'], $value[static::$filter]);

                    if (in_array($filter, $valuesToFilter, true)) {
                        $filtered[$key] = $value;
                    }
                }
            }
            $dataToUse = $filtered;
        }

        if ((static::$filterAndKeys || ($filter === null)) && ($keys !== null)) {
            $dataToUse = array_intersect_key($dataToUse, array_fill_keys($keys, true));
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

    protected static function filterCallback($value)
    {
        return $value;
    }

    /**
     * @param null|array|mixed|string $default
     */
    public static function flatten(string $field = null, $default = null, string $filter = null, array $keys = null, string $prefix = null, string $postfix = null): array
    {
        $array = [];
        foreach (static::filter($filter, $keys) as $key => $value) {
            $array[$prefix . $key . $postfix] = $value[$field ?: static::$label] ?? $default;
        }

        return $array;
    }

    public static function get(string|int|bool $key = null): ?array
    {
        return static::_data()[$key] ?? null;
    }

    public static function format(string|int|bool|null $key, string $format, string $default = null, array $fields = null): ?string
    {
        if (($key !== null) && ($data = static::_data()[$key] ?? null)) {
            $values = $fields ? self::fields($key, $fields) : $data;

            return sprintf($format, ...array_values($values));
        }

        return $default;
    }

    public static function formatWithKey(string|int|bool $key, string $format, string $default = null, array $fields = null): ?string
    {
        if ($data = static::_data()[$key] ?? null) {
            $values = $fields ? self::fields($key, $fields) : $data;

            return sprintf($format, $key, ...array_values($values));
        }

        return $default;
    }

    public static function formatCallback(string|int|bool $key, callable $callback, string $default = null, array $fields = null): ?string
    {
        if ($data = static::_data()[$key] ?? null) {
            $values = $fields ? self::fields($key, $fields) : $data;

            return $callback(...array_values($values));
        }

        return $default;
    }

    /**
     * @param null|mixed $default
     */
    public static function label(string|int|bool $key = null, $default = null, string $field = null): ?string
    {
        if (($key !== null) && (isset(static::_data()[$key][$field ?: static::$label]))) {
            return static::_data()[$key][$field ?: static::$label];
        }

        return $default;
    }

    /**
     * @param null|mixed $default
     *
     * @return null|mixed|string
     */
    public static function field(string|int|bool $key = null, string $field = null, $default = null): mixed
    {
        if (($key !== null) && (isset(static::_data()[$key][$field ?: static::$label]))) {
            return static::_data()[$key][$field ?: static::$label];
        }

        return $default;
    }

    /**
     * @param null|mixed $default
     *
     * @return null|mixed|string
     */
    public static function fields(string|int|bool $key = null, array $fields = [], $default = null): mixed
    {
        if (($key !== null) && (isset(static::_data()[$key]))) {
            $fieldsAll      = static::_data()[$key];
            $fieldsFiltered = [];
            foreach ($fields as $field) {
                $fieldsFiltered[$field] = $fieldsAll[$field] ?? $default;
            }

            return $fieldsFiltered;
        }

        return $default;
    }

    /**
     * @return false|int|string
     */
    public static function random()
    {
        $keys = array_keys(static::_data());
        shuffle($keys);

        return reset($keys);
    }

    public static function count(string $filter = null): int
    {
        return count(self::keys($filter));
    }

}
