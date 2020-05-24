<?php

namespace HBM\BasicsBundle\Util\AttributeMessage;

use HBM\BasicsBundle\Util\Result\Message;

class AttributeMessage {

  /**
   * @var string
   */
  private $attribute;

  /**
   * @var Message
   */
  private $message;

  /**
   * VoterWrapper constructor.
   *
   * @param string|null $attribute
   * @param Message|null $message
   */
  public function __construct(string $attribute = NULL, Message $message = NULL) {
    $this->attribute = $attribute;
    $this->message = $message;
  }

  /**
   * Set attribute.
   *
   * @param string $attribute
   *
   * @return self
   */
  public function setAttribute(string $attribute = NULL) : self {
    $this->attribute = $attribute;

    return $this;
  }

  /**
   * Get attribute.
   *
   * @return string|null
   */
  public function getAttribute() : ?string {
    return $this->attribute;
  }

  /**
   * Set message.
   *
   * @param Message $message
   *
   * @return self
   */
  public function setMessage(Message $message = NULL) : self {
    $this->message = $message;

    return $this;
  }

  /**
   * Get message.
   *
   * @return Message|null
   */
  public function getMessage() : ?Message {
    return $this->message;
  }

}
