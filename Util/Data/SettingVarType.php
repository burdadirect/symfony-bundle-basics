<?php

namespace HBM\Basics\Util\Data;

class SettingVarType extends AbstractData {

  public const INT     = 'int';
  public const FLOAT   = 'float';
  public const STRING  = 'string';
  public const BOOLEAN = 'boolean';
  public const CSV     = 'csv';
  public const JSON    = 'json';

  public static $data = [
    self::INT => [
      'text' => 'Ganzzahl',
    ],
    self::FLOAT => [
      'text' => 'Fließkommazahl',
    ],
    self::STRING => [
      'text' => 'Text',
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
  ];

}
