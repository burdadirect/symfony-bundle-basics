<?php

namespace HBM\BasicsBundle\Util\Result;

use Doctrine\Common\Collections\ArrayCollection;
use HBM\BasicsBundle\Entity\Interfaces\NoticeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Result {

  /**
   * @var ArrayCollection|Message[]
   */
  protected $messages;

  /**
   * @var ArrayCollection|NoticeInterface[]
   */
  protected $notices;

  /**
   * @var bool
   */
  protected $return;

  /**
   * @var array
   */
  protected $payloads;

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

    $this->messages = new ArrayCollection();
    $this->notices = new ArrayCollection();
    $this->payloads = [];
  }

  /**
   * Set return.
   *
   * @param bool|null $return
   *
   * @return self
   */
  public function setReturn(?bool $return) : self {
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
   * Set payloads.
   *
   * @param array $payloads
   *
   * @return self
   */
  public function setPayloads(array $payloads) : self {
    $this->payloads = $payloads;

    return $this;
  }

  /**
   * Get payloads.
   *
   * @return array
   */
  public function getPayloads() : array {
    return $this->payloads;
  }

  /**
   * Add message.
   *
   * @param Message $message
   *
   * @return self
   */
  public function addMessage(Message $message) : self {
    if (!$this->messages->contains($message)) {
      $this->messages->add($message);
    }

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
    if ($this->messages->contains($message)) {
      $this->messages->remove($message);
    }

    return $this;
  }

  /**
   * Get mesages.
   *
   * @return Message[]
   */
  public function getMessages() : array {
    return $this->messages->toArray();
  }

  /**
   * Add notice.
   *
   * @param NoticeInterface $notice
   *
   * @return self
   */
  public function addNotice(NoticeInterface $notice) : self {
    if (!$this->notices->contains($notice)) {
      $this->notices->add($notice);
    }

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
    if ($this->notices->contains($notice)) {
      $this->notices->remove($notice);
    }

    return $this;
  }

  /**
   * Get notices.
   *
   * @return NoticeInterface[]
   */
  public function getNotices() : array {
    return $this->notices->toArray();
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

  /**
   * Add messages.
   *
   * @param array|Message[] $messages
   *
   * @return self
   */
  public function addMessages(array $messages) : self {
    foreach ($messages as $message) {
      $this->addMessage($message);
    }

    return $this;
  }

  /**
   * Merge another result to this one.
   *
   * @param Result $result
   *
   * @return self
   */
  public function merge(Result $result) : self {
    $this->addMessages($result->getMessages());

    if (($this->getReturn() === FALSE) || ($result->getReturn() === FALSE)) {
      $this->setReturn(FALSE);
    } elseif (($this->getReturn() === NULL) || ($result->getReturn() === NULL)) {
      $this->setReturn(NULL);
    }

    return $this;
  }

  /****************************************************************************/

  /**
   * @param string $key
   *
   * @return mixed|null
   */
  public function getPayload(string $key) {
    return $this->payloads[$key] ?? NULL;
  }

  /**
   * @param string $key
   * @param mixed $payload
   *
   * @return $this
   */
  public function setPayload(string $key, $payload) : self {
    $this->payloads[$key] = $payload;

    return $this;
  }

  /****************************************************************************/

  /**
   * @return JsonResponse
   */
  public function jsonResponse() : JsonResponse {
    $status = $this->getReturn() ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

    return new JsonResponse(['success' => $this->getReturn(), 'messages' => $this->getMessagesArray()], $status);
  }

}
