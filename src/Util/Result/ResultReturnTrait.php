<?php

namespace HBM\BasicsBundle\Util\Result;

trait ResultReturnTrait
{

    protected ?bool $return = null;

    public function setReturn(?bool $return): self
    {
        $this->return = $return;

        return $this;
    }

    public function getReturn(): ?bool
    {
        return $this->return;
    }

}
