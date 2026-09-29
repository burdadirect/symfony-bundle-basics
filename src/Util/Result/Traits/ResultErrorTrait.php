<?php

namespace HBM\BasicsBundle\Util\Result\Traits;

trait ResultErrorTrait
{

    protected ?string $error = null;

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(?string $error): static
    {
        $this->error = $error;

        return $this;
    }

}
