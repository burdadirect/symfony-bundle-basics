<?php

namespace HBM\BasicsBundle\Util\Result\Traits;

trait ResultReturnTrait
{

    protected ?bool $return = null;

    public function setReturn(?bool $return): static
    {
        $this->return = $return;

        return $this;
    }

    public function getReturn(): ?bool
    {
        return $this->return;
    }

}
