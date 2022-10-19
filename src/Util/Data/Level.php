<?php

namespace HBM\BasicsBundle\Util\Data;

class Level extends AbstractData {

  public const INFO    = 'info';
  public const SUCCESS = 'success';
  public const WARNING = 'warning';
  public const ERROR   = 'error';

  public static array $data = [
    self::INFO => [
      'text' => 'info',
      'alert' => 'info',
    ],
    self::SUCCESS => [
      'text' => 'success',
      'alert' => 'success',
    ],
    self::WARNING => [
      'text' => 'warning',
      'alert' => 'warning',
    ],
    self::ERROR => [
      'text' => 'error',
      'alert' => 'danger',
    ],
  ];

  /****************************************************************************/

  public static function getAlertLevel(string $key, string $default = Level::INFO) : ?string {
    return static::$data[$key]['alert'] ?? $default;
  }

}
