<?php

namespace HBM\BasicsBundle\Util\Result;

use HBM\BasicsBundle\Util\Enum\Level;

class Message
{
    private ?Level $level;
    private ?string $message;

    public function __construct(string $message, Level $level = Level::INFO)
    {
        $this->message = $message;
        $this->level   = $level;
    }

    public function setLevel(?string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

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
        if ($console = $this->getLevel()->console()) {
            $format = '<' . $console . '>%s</' . $console . '>';
        }

        return sprintf($format, $this->formatMessage(...func_get_args()));
    }

}
