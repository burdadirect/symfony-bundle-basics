<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

interface Uuidable extends Addressable
{
    /**
     * Get UUID
     */
    public function getUuid(): string;
}
