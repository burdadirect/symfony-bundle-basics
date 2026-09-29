<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

interface Timestampable extends Addressable
{
    public function setCreated(\DateTime|string|null $created): static;
    public function getCreated(): ?\DateTime;

    public function setModified(\DateTime|string|null $modified): static;
    public function getModified(): ?\DateTime;
}
