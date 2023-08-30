<?php

namespace HBM\BasicsBundle\Security\Authenticator\Passport\Badge;

use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

class SuppressLastLoginBadge implements BadgeInterface
{
    public function isResolved(): bool
    {
        return true;
    }
}
