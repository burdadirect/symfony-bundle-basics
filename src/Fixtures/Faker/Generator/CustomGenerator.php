<?php

namespace HBM\BasicsBundle\Fixtures\Faker\Generator;

use Faker\Generator;
use HBM\BasicsBundle\Fixtures\Faker\Provider\EmailsProvider;
use HBM\BasicsBundle\Fixtures\Faker\Provider\RandomArrayProvider;
use HBM\BasicsBundle\Fixtures\Faker\Provider\SafeCanonicalEmailProvider;
use HBM\BasicsBundle\Fixtures\Faker\Provider\UrlsProvider;
/**
 * @mixin EmailsProvider
 * @mixin UrlsProvider
 * @mixin RandomArrayProvider
 * @mixin SafeCanonicalEmailProvider
 *
 * @method CustomGenerator unique($reset = false, $maxRetries = 10000)
 * @method CustomGenerator optional(float $weight = 0.5, $default = null)
 */
class CustomGenerator extends Generator
{
}
