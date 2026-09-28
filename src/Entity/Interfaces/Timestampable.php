<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

interface Timestampable extends Addressable
{
    public function setCreated(\DateTime|string|null $created): self;
    public function getCreated(): ?\DateTime;

    public function setModified(\DateTime|string|null $modified): self;
    public function getModified(): ?\DateTime;
}
