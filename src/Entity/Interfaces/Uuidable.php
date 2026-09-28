<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

interface Uuidable extends Addressable
{
    public function getUuid(): string;
}
