<?php

namespace HBM\BasicsBundle\Util\Result;

use Doctrine\Common\Collections\ArrayCollection;
use HBM\BasicsBundle\Entity\Interfaces\NoticeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Result
{
    use ResultReturnTrait;
    use ResultPayloadTrait;

    /** @var ArrayCollection|Message[] */
    protected $messages;

    /** @var ArrayCollection|NoticeInterface[] */
    protected $notices;

    public ?string $error = null;

    /**
     * Result constructor.
     */
    public function __construct(?bool $return = null)
    {
        $this->return = $return;

        $this->messages = new ArrayCollection();
        $this->notices  = new ArrayCollection();
    }

    /**
     * Add message.
     */
    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
        }

        return $this;
    }

    /**
     * Remove message.
     */
    public function removeMessage(Message $message): self
    {
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
    public function getMessages(): array
    {
        return $this->messages->toArray();
    }

    /**
     * Add notice.
     */
    public function addNotice(NoticeInterface $notice): self
    {
        if (!$this->notices->contains($notice)) {
            $this->notices->add($notice);
        }

        return $this;
    }

    /**
     * Remove notice.
     */
    public function removeNotice(NoticeInterface $notice): self
    {
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
    public function getNotices(): array
    {
        return $this->notices->toArray();
    }

    /* CUSTOM */

    public function addMessageByString($level, $message): self
    {
        return $this->addMessage(new Message($message, $level));
    }

    public function getMessagesPlain(): array
    {
        $messages = [];
        foreach ($this->getMessages() as $message) {
            $messages[] = $message->getMessage();
        }

        return $messages;
    }

    public function getMessagesArray(): array
    {
        $messages = [];
        foreach ($this->getMessages() as $message) {
            $messages[] = [
                'text'  => $message->getMessage(),
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
     */
    public function addMessages(array $messages): self
    {
        foreach ($messages as $message) {
            $this->addMessage($message);
        }

        return $this;
    }

    /**
     * Merge another result to this one.
     */
    public function merge(Result $result): self
    {
        $this->addMessages($result->getMessages());
        foreach ($result->getPayloads() as $payloadKey => $payloadValue) {
            $this->setPayload($payloadKey, $payloadValue);
        }

        if (($this->getReturn() === false) || ($result->getReturn() === false)) {
            $this->setReturn(false);
        } elseif (($this->getReturn() === null) || ($result->getReturn() === null)) {
            $this->setReturn(null);
        }

        return $this;
    }

    public function jsonResponse(): JsonResponse
    {
        $status = $this->getReturn() ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

        return new JsonResponse(['success' => $this->getReturn(), 'messages' => $this->getMessagesArray()], $status);
    }
}
