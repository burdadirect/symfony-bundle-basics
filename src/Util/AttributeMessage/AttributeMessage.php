<?php

namespace HBM\BasicsBundle\Util\AttributeMessage;

use HBM\BasicsBundle\Util\Result\Message;

class AttributeMessage
{
    private ?string $attribute;

    private ?Message $message;

    public function __construct(?string $attribute = null, ?Message $message = null)
    {
        $this->attribute = $attribute;
        $this->message   = $message;
    }

    public function setAttribute(?string $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getAttribute(): ?string
    {
        return $this->attribute;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }
}
