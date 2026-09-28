<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

use HBM\BasicsBundle\Util\Enum\SettingVarType;

interface SettingInterface extends Addressable
{
    public function getVarType(): ?SettingVarType;

    public function getVarValueParsed();
}
