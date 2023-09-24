<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

interface SettingInterface extends Addressable
{
    /**
     * Get varType.
     */
    public function getVarType(): ?string;

    /**
     * Get varValueParsed.
     */
    public function getVarValueParsed();
}
