<?php

namespace HBM\BasicsBundle\Fixtures\Faker\Provider;

use Faker\Provider\Internet as BaseProvider;
use HBM\BasicsBundle\Util\Canonicalizer;

final class SafeCanonicalEmailProvider extends BaseProvider
{
    public function safeCanonicalEmail(): string
    {
        return Canonicalizer::canonicalize($this->safeEmail());
    }
}
