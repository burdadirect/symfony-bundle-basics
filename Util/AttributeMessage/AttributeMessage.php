<?php

namespace HBM\BasicsBundle\Util\AttributeMessage;

use HBM\BasicsBundle\Util\Result\Message;

class AttributeMessage {

  private ?string $attribute;

  private ?Message $message;

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
   * @param string|null $attribute
   *
   * @return self
   */
  public function setAttribute(?string $attribute) : self {
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
   * @param Message|null $message
   *
   * @return self
   */
  public function setMessage(?Message $message) : self {
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
