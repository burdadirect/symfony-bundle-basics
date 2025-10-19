<?php

namespace HBM\BasicsBundle\Util\Result;

use HBM\BasicsBundle\Util\Data\Level;

class Message
{
    private ?string $level;

    private ?string $message;

    /**
     * Message constructor.
     */
    public function __construct(string $message, string $level = Level::INFO)
    {
        $this->message = $message;
        $this->level   = $level;
    }

    /**
     * Set level.
     */
    public function setLevel(?string $level): self
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
     * Set message.
     */
    public function setMessage(?string $message): self
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

    public function formatMessage(): ?string
    {
        $message = $this->getMessage();
        if (func_num_args() > 0) {
            $message = sprintf($message, ...func_get_args());
        }

        return $message;
    }

    public function formatMessageConsole(): ?string
    {
        $format = '%s';
        if ($console = Level::field($this->getLevel(), 'console')) {
            $format = '<' . $console . '>%s</' . $console . '>';
        }

        return sprintf($format, $this->formatMessage(...func_get_args()));
    }

    public function getAlertLevel(): string
    {
        return Level::getAlertLevel($this->getLevel());
    }
}
