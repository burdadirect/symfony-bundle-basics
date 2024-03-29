<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;
use Symfony\Contracts\Service\Attribute\Required;

trait FormLoginAuthenticatorDependencyTrait
{
    protected FormLoginAuthenticator $formLoginAuthenticator;

    #[Required]
    public function setFormLoginAuthenticator(FormLoginAuthenticator $formLoginAuthenticator): void
    {
        $this->formLoginAuthenticator = $formLoginAuthenticator;
    }
}
