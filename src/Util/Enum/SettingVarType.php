<?php

namespace HBM\BasicsBundle\Util\Enum;

use HBM\BasicsBundle\Util\Enum\Interfaces\EnumInterface;
use HBM\BasicsBundle\Util\Enum\Traits\EnumTrait;

enum SettingVarType: string implements EnumInterface
{
    use EnumTrait;

    case INT     = 'int';
    case FLOAT   = 'float';
    case STRING  = 'string';
    case HTML    = 'html';
    case BOOLEAN = 'boolean';
    case CSV     = 'csv';
    case JSON    = 'json';

    public function fields(): array
    {
        return match ($this) {
            self::INT => [
                'text' => 'Ganzzahl',
            ],
            self::FLOAT => [
                'text' => 'Fließkommazahl',
            ],
            self::STRING => [
                'text' => 'Text',
            ],
            self::HTML => [
                'text' => 'HTML',
            ],
            self::BOOLEAN => [
                'text' => 'Wahrheitswert',
            ],
            self::CSV => [
                'text' => 'CSV',
            ],
            self::JSON => [
                'text' => 'JSON',
            ],
        };
    }

    public function label(): string
    {
        return $this->field('text');
    }
}
