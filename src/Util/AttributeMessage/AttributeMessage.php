<?php

namespace HBM\BasicsBundle\Util\AttributeMessage;

use HBM\BasicsBundle\Util\Result\Message;

class AttributeMessage
{
    private ?string $attribute;

    private ?Message $message;

    /**
     * VoterWrapper constructor.
     */
    public function __construct(string $attribute = null, Message $message = null)
    {
        $this->attribute = $attribute;
        $this->message   = $message;
    }

    /**
     * Set attribute.
     */
    public function setAttribute(?string $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Get attribute.
     */
    public function getAttribute(): ?string
    {
        return $this->attribute;
    }

    /**
     * Set message.
     */
    public function setMessage(?Message $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     */
    public function getMessage(): ?Message
    {
        return $this->message;
    }
}
