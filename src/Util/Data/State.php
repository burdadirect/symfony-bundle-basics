<?php

namespace HBM\BasicsBundle\Util\Data;

class State extends AbstractData
{
    public const int REVIEW  = -2;
    public const int BLOCKED = -1;
    public const int PENDING = 0;
    public const int ACTIVE  = 1;

    public static array $data = [
        self::REVIEW => [
            'text' => 'zurückgestellt',
        ],
        self::BLOCKED => [
            'text' => 'gesperrt',
        ],
        self::PENDING => [
            'text' => 'wartend',
        ],
        self::ACTIVE => [
            'text' => 'aktiv',
        ],
    ];
}
