<?php

namespace HBM\BasicsBundle\Fixtures\Faker\Provider;

use Faker\Provider\Base as BaseProvider;

final class RandomArrayProvider extends BaseProvider
{
    /**
     * @param array    $array               array to take elements from
     * @param null|int $min                 minimum number of random array elements
     * @param null|int $max                 maximum number of random array elements
     * @param float    $chanceForEmptyArray the chance for a non empty array
     * @param bool     $allowDuplicates     Allow elements to be picked several times. Defaults to false.
     */
    public function randomArray(array $array, int $min = null, int $max = null, float $chanceForEmptyArray = 0, bool $allowDuplicates = false): array
    {
        if ($min === null) {
            $min = 0;
        } elseif ($min < 0) {
            throw new \LogicException(sprintf('Minimum number of random array elements must be >= 0.'));
        }

        if ($max === null) {
            $max = \count($array);
        }

        $numberOfArrayElements = $this->generator->numberBetween($min, $max);

        return $this->generator
          ->optional(1 - $chanceForEmptyArray, [])
          ->randomElements($array, $numberOfArrayElements, $allowDuplicates);
    }
}
