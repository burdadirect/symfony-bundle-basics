<?php

namespace HBM\BasicsBundle\Util\Data;

class Gender extends AbstractData {

  public const M  =  'm';
  public const W  =  'w';
  public const D  =  'd';
  public const T  =  't';

  public static array $data = [
    self::M => [
      'text' => 'männlich',
      'short' => 'männlich',
      'aliases' => ['m', 'männlich', 'herr', 'mann', 'sehr geehrter', 'sehr geehrter herr', 'lieber herr'],
      'icon' => 'mars',
      'color' => '#add8e6',
    ],
    self::W => [
      'text' => 'weiblich',
      'short' => 'weiblich',
      'aliases' => ['w', 'weiblich', 'frau', 'sehr geehrte', 'sehr geehrte frau', 'liebe frau'],
      'icon' => 'venus',
      'color' => '#ffc0cb',
    ],
    self::D => [
      'text' => 'divers / nicht-binär / intersexuell',
      'short' => 'divers',
      'aliases' => [
        'd', 'divers', 'neutral', 'neuter',
        'n', 'nonbinary', 'non-binary', 'nicht binär', 'genderless', 'geschlechtslos',
        'i', 'inter', 'intersex', 'intersexuell', 'inter',
        'fluid', 'genderfluid',
      ],
      'icon' => 'genderless',
      'color' => '#cccccc',
    ],
    self::T => [
      'text' => 'transgender',
      'short' => 'trans',
      'aliases' => [
        'trans', 'transgender', 'trans-gender', 'transsexuell', 'trans-sexuell',
        'tm',  'transmann', 'trans-mann',
        'tf', 'transfrau', 'trans-frau',
      ],
      'icon' => 'transgender',
      'color' => '#cccccc',
    ],
  ];

  /**
   * Determine gender from various strings.
   *
   * @param string|null $gender
   * @return string|null
   */
  public static function determine(?string $gender) : ?string {
    if ($gender) {
      $gender = mb_strtolower($gender);

      foreach (self::data() as $key => $value) {
        if (in_array($gender, $value['aliases'], TRUE)) {
          return $key;
        }
      }
    }

    return NULL;
  }

}
