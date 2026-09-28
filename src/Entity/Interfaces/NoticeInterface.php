<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

use HBM\BasicsBundle\Util\Enum\Interfaces\EnumInterface;
use HBM\BasicsBundle\Util\Enum\Level;

interface NoticeInterface extends Addressable
{
    public function getTitle(): ?string;
    public function setTitle(?string $title): self;

    public function getMessage(): ?string;
    public function setMessage(?string $message): self;

    public function getLevel(): Level;
    public function setLevel(Level $level): self;

    public function getMode(): ?EnumInterface;
    public function setMode(?EnumInterface $mode): self;
}
