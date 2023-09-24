<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait UserAuthenticatorDependencyTrait
{
    protected UserAuthenticatorInterface $userAuthenticator;

    #[Required]
    public function setUserAuthenticator(UserAuthenticatorInterface $userAuthenticator): void
    {
        $this->userAuthenticator = $userAuthenticator;
    }
}
