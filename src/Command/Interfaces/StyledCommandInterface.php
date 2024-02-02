<?php

namespace HBM\BasicsBundle\Command\Interfaces;

use HBM\BasicsBundle\Command\AbstractExtendableCommand;
use HBM\BasicsBundle\Command\Attributes\Extendable\PreExecute;

interface StyledCommandInterface
{
    #[PreExecute(1000)]
    public function styleOutput(AbstractExtendableCommand $command, ?int &$exitCode): bool;
}
