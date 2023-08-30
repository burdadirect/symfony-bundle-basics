<?php

namespace HBM\BasicsBundle\Entity\Traits;

use HBM\BasicsBundle\Util\Data\Level;

trait NoticeTrait
{
    /* PROPERTIES */

    /** @var string */
    protected $level = Level::INFO;

    /** @var string */
    protected $mode;

    /** @var string */
    protected $title;

    /** @var string */
    protected $message;

    /* CONSTRUCTOR / GETTER / SETTER */

    /**
     * Set level.
     *
     * @param string $level
     */
    public function setLevel($level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level.
     */
    public function getLevel(): ?string
    {
        return $this->level;
    }

    /**
     * Set mode.
     *
     * @param string $mode
     */
    public function setMode($mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Get mode.
     */
    public function getMode(): ?string
    {
        return $this->mode;
    }

    /**
     * Set title.
     *
     * @param string $title
     */
    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set message.
     *
     * @param string $message
     */
    public function setMessage($message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /* CUSTOM */

    public function getAlertLevel(): string
    {
        return Level::getAlertLevel($this->getLevel());
    }
}
