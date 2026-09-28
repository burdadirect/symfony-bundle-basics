<?php

namespace HBM\BasicsBundle\Util\Enum;

use HBM\BasicsBundle\Util\Enum\Interfaces\EnumInterface;
use HBM\BasicsBundle\Util\Enum\Traits\EnumTrait;

enum Level: string implements EnumInterface
{
    use EnumTrait;

    case INFO    = 'info';
    case SUCCESS = 'success';
    case WARNING = 'warning';
    case ERROR   = 'error';

    public function fields(): array
    {
        return match ($this) {
            self::INFO => [
                'text'    => 'Info',
                'flash'   => 'info',
                'alert'   => 'info',
                'console' => 'note',
            ],
            self::SUCCESS => [
                'text'    => 'Erfolg',
                'flash'   => 'success',
                'alert'   => 'success',
                'console' => 'success',
            ],
            self::WARNING => [
                'text'    => 'Warnung',
                'flash'   => 'warning',
                'alert'   => 'warning',
                'console' => 'warning',
            ],
            self::ERROR => [
                'text'    => 'Fehler',
                'flash'   => 'error',
                'alert'   => 'danger',
                'console' => 'failure',
            ],
        };
    }

    public function label(): string
    {
        return $this->field('text');
    }

    public function flash(): string
    {
        return $this->field('flash');
    }
    public function alert(): string
    {
        return $this->field('alert');
    }
    public function console(): string
    {
        return $this->field('console');
    }

    public function isInfo(): bool
    {
        return $this === self::INFO;
    }
    public function isSuccess(): bool
    {
        return $this === self::SUCCESS;
    }
    public function isWarning(): bool
    {
        return $this === self::WARNING;
    }
    public function isError(): bool
    {
        return $this === self::ERROR;
    }
}
