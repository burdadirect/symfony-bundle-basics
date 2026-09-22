<?php

namespace HBM\BasicsBundle\Util;

class Url
{
    public static function appendQueryParams(string $url, array $params): string
    {
        $parsed = parse_url($url);
        $query  = [];

        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $query);
        }

        $query       = array_merge($query, $params);
        $queryString = http_build_query($query);

        $scheme    = isset($parsed['scheme']) ? $parsed['scheme'] . '://' : '';
        $host      = $parsed['host'] ?? '';
        $port      = isset($parsed['port']) ? ':' . $parsed['port'] : '';
        $user      = $parsed['user'] ?? '';
        $pass      = isset($parsed['pass']) ? ':' . $parsed['pass'] : '';
        $pass      = ($user || $pass) ? "{$pass}@" : '';
        $path      = $parsed['path'] ?? '';
        $queryPart = $queryString ? '?' . $queryString : '';
        $fragment  = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';

        return "{$scheme}{$user}{$pass}{$host}{$port}{$path}{$queryPart}{$fragment}";
    }
}
