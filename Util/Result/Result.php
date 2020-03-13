<?php

namespace HBM\BasicsBundle\Util\Result;

use HBM\BasicsBundle\Entity\Interfaces\NoticeInterface;

class Result {

  /**
   * @var Message[]
   */
  private $mesages;

  /**
   * @var NoticeInterface[]
   */
  private $notices;

  /**
   * @var bool
   */
  private $return;

  /**
   * @var mixed
   */
  private $payload;

  /**
   * @var string
   */
  public $error;


  /**
   * Result constructor.
   *
   * @param bool|NULL $return
   */
  public function __construct(bool $return = NULL) {
    $this->return = $return;

    $this->mesages = [];
    $this->notices = [];
  }

  /**
   * Set return.
   *
   * @param bool $return
   *
   * @return self
   */
  public function setReturn($return) : self {
    $this->return = $return;

    return $this;
  }

  /**
   * Get return.
   *
   * @return bool|null
   */
  public function getReturn() : ?bool {
    return $this->return;
  }

  /**
   * Set payload.
   *
   * @param mixed $payload
   *
   * @return self
   */
  public function setPayload($payload = NULL) : self {
    $this->payload = $payload;

    return $this;
  }

  /**
   * Get payload.
   *
   * @return mixed|null
   */
  public function getPayload() {
    return $this->payload;
  }

  /**
   * Add message.
   *
   * @param Message $message
   *
   * @return self
   */
  public function addMessage(Message $message) : self {
    $this->mesages[] = $message;

    return $this;
  }

  /**
   * Remove message.
   *
   * @param Message $message
   *
   * @return self
   */
  public function removeMessage(Message $message) : self {
    foreach ($this->mesages as $key => $value) {
      if ($value->getUuid() === $message->getUuid()) {
        unset($this->mesages[$key]);
      }
    }

    return $this;
  }

  /**
   * Get mesages.
   *
   * @return Message[]
   */
  public function getMessages() : array {
    return $this->mesages;
  }

  /**
   * Add notice.
   *
   * @param NoticeInterface $notice
   *
   * @return self
   */
  public function addNotice(NoticeInterface $notice) : self {
    $this->notices[] = $notice;

    return $this;
  }

  /**
   * Remove notice.
   *
   * @param NoticeInterface $notice
   *
   * @return self
   */
  public function removeNotice(NoticeInterface $notice) : self {
    foreach ($this->notices as $key => $value) {
      if ($value->getUuid() === $notice->getUuid()) {
        unset($this->notices[$key]);
      }
    }

    return $this;
  }

  /**
   * Get notices.
   *
   * @return NoticeInterface[]
   */
  public function getNotices() : array {
    return $this->notices;
  }

  /****************************************************************************/
  /* CUSTOM                                                                   */
  /****************************************************************************/

  public function addMessageByString($level, $message) : void {
    $this->addMessage(new Message($message, $level));
  }

  public function getMessagesPlain() : array {
    $messages = [];
    foreach ($this->getMessages() as $message) {
      $messages[] = $message->getMessage();
    }
    return $messages;
  }

  public function getMessagesArray() : array {
    $messages = [];
    foreach ($this->getMessages() as $message) {
      $messages[] = [
        'text' => $message->getMessage(),
        'level' => $message->getLevel(),
        'alert' => $message->getAlertLevel(),
      ];
    }
    return $messages;
  }

}
