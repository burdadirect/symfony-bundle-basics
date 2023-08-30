<?php

namespace HBM\BasicsBundle\Security\Authenticator\Passport\Badge;

use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

class ImplicitLoginBadge implements BadgeInterface
{
    private string $mode;

    public function __construct(string $mode)
    {
        $this->mode = $mode;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function isResolved(): bool
    {
        return true;
    }
}
