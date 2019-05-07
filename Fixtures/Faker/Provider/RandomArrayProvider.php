<?php

namespace HBM\BasicsBundle\Fixtures\Faker\Provider;

use Faker\Provider\Base as BaseProvider;

final class RandomArrayProvider extends BaseProvider {

  /**
   * @param array  $array                Array to take elements from.
   * @param int    $min                  Minimum number of random array elements.
   * @param int    $max                  Maximum number of random array elements.
   * @param int    $chanceForEmptyArray  The chance for a non empty array.
   * @param bool   $allowDuplicates      Allow elements to be picked several times. Defaults to false.
   *
   * @return array
   */
  public function randomArray(array $array, int $min = NULL, int $max = NULL, int $chanceForEmptyArray = 0, $allowDuplicates = FALSE) : array {
    if ($min === NULL) {
      $min = 0;
    } elseif ($min < 0) {
      throw new \LogicException(sprintf('Minimum number of random array elements must be >= 0.'));
    }

    if ($max === NULL) {
      $max = \count($array);
    }

    $numberOfArrayElements = $this->generator->numberBetween($min, $max);

    return $this->generator
      ->optional(100 - $chanceForEmptyArray, [])
      ->randomElements($array, $numberOfArrayElements, $allowDuplicates);
  }

}

