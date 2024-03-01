<?php

namespace HBM\BasicsBundle\EnvVarProcessor;

use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

class RandomEnvVarProcessor implements EnvVarProcessorInterface
{
    public function getEnv(string $prefix, string $name, \Closure $getEnv): string
    {
        $env = $getEnv($name);

        try {
            $random = bin2hex(random_bytes(10));
        } catch (\Throwable) {
            $random = uniqid('', true);
        }

        if ($prefix === 'randomPrefix') {
            return $random . $env;
        }

        if ($prefix === 'randomPostfix') {
            return $env . $random;
        }

        if ($prefix === 'randomFallback' && empty($env)) {
            return $random;
        }

        if ($prefix === 'random') {
            return $random;
        }

        return $env;
    }

    public static function getProvidedTypes(): array
    {
        return [
            'random'         => 'string',
            'randomPrefix'   => 'string',
            'randomPostfix'  => 'string',
            'randomFallback' => 'string',
        ];
    }
}
