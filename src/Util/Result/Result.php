<?php

namespace HBM\BasicsBundle\Util\Result;

use Doctrine\Common\Collections\ArrayCollection;
use HBM\BasicsBundle\Entity\Interfaces\NoticeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Result
{
    /** @var ArrayCollection|Message[] */
    protected $messages;

    /** @var ArrayCollection|NoticeInterface[] */
    protected $notices;

    protected ?bool $return;

    protected ?array $payloads;

    public ?string $error;

    /**
     * Result constructor.
     */
    public function __construct(bool $return = null)
    {
        $this->return = $return;

        $this->messages = new ArrayCollection();
        $this->notices  = new ArrayCollection();
        $this->payloads = [];
    }

    /**
     * Set return.
     */
    public function setReturn(?bool $return): self
    {
        $this->return = $return;

        return $this;
    }

    /**
     * Get return.
     */
    public function getReturn(): ?bool
    {
        return $this->return;
    }

    /**
     * Set payloads.
     */
    public function setPayloads(array $payloads): self
    {
        $this->payloads = $payloads;

        return $this;
    }

    /**
     * Get payloads.
     */
    public function getPayloads(): array
    {
        return $this->payloads;
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

        if (($this->getReturn() === false) || ($result->getReturn() === false)) {
            $this->setReturn(false);
        } elseif (($this->getReturn() === null) || ($result->getReturn() === null)) {
            $this->setReturn(null);
        }

        return $this;
    }

    /**
     * @return null|mixed
     */
    public function getPayload(string $key)
    {
        return $this->payloads[$key] ?? null;
    }

    /**
     * @return $this
     */
    public function setPayload(string $key, $payload): self
    {
        $this->payloads[$key] = $payload;

        return $this;
    }

    public function jsonResponse(): JsonResponse
    {
        $status = $this->getReturn() ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

        return new JsonResponse(['success' => $this->getReturn(), 'messages' => $this->getMessagesArray()], $status);
    }
}
