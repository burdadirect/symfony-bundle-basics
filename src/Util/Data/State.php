<?php

namespace HBM\BasicsBundle\Util\Data;

class State extends AbstractData {

  public const REVIEW  = -2;
  public const BLOCKED = -1;
  public const PENDING =  0;
  public const ACTIVE  =  1;

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
