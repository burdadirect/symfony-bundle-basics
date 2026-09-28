<?php

namespace HBM\BasicsBundle\Entity\Traits;

use HBM\BasicsBundle\Util\Enum\Interfaces\EnumInterface;
use HBM\BasicsBundle\Util\Enum\Level;

trait NoticeTrait
{
    /* PROPERTIES */

    protected Level $level = Level::INFO;
    protected ?EnumInterface $mode = null;
    protected ?string $title = null;
    protected ?string $message = null;

    /* CONSTRUCTOR / GETTER / SETTER */

    public function getLevel(): Level
    {
        return $this->level;
    }

    public function setLevel(Level $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getMode(): ?EnumInterface
    {
        return $this->mode;
    }

    public function setMode(?EnumInterface $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
