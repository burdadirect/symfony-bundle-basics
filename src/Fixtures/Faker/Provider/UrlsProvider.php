<?php

namespace HBM\BasicsBundle\Fixtures\Faker\Provider;

use Faker\Provider\Base as BaseProvider;

final class UrlsProvider extends BaseProvider
{
    /**
     * @param int  $min                 minimum number of random array elements
     * @param int  $max                 maximum number of random array elements
     * @param int  $chanceForEmptyArray the chance for a non empty array
     * @param bool $allowDuplicates     Allow duplicates. Defaults to false.
     */
    public function urls(int $min = 0, int $max = 10, int $chanceForEmptyArray = 0, $allowDuplicates = true): array
    {
        $urls = [];

        if ($this->generator->boolean(100 - $chanceForEmptyArray)) {
            $numberOfUrls = $this->generator->numberBetween($min, $max);

            $generator = $this->generator;

            if (!$allowDuplicates) {
                $generator = $generator->unique(true);
            }
            for ($i = 0; $i < $numberOfUrls; ++$i) {
                $urls[] = $generator->url();
            }
        }

        return $urls;
    }
}
