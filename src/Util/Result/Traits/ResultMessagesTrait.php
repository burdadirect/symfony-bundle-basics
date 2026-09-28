<?php

namespace HBM\BasicsBundle\Util\Result\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use HBM\BasicsBundle\Util\Enum\Level;
use HBM\BasicsBundle\Util\Result\Interfaces\ResultMessagesInterface;
use HBM\BasicsBundle\Util\Result\Message;

/**
 * @phpstan-require-implements ResultMessagesInterface
 * @psalm-require-implements ResultMessagesInterface
 */
trait ResultMessagesTrait
{
    /** @var Collection<array-key, Message> */
    protected Collection $messages;

    protected function initMessages(): void
    {
        $this->messages = new ArrayCollection();
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
        }

        return $this;
    }

    public function addMessageByString(Level $level, string $message): self
    {
        return $this->addMessage(new Message($message, $level));
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
        }

        return $this;
    }

    /**
     * @return Collection<array-key, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function getMessagesPlain(): array
    {
        $messages = [];
        foreach ($this->getMessages() as $message) {
            $messages[] = $message->getMessage();
        }

        return $messages;
    }

    /**
     * @return array<array{
     *     text: string,
     *     level: string,
     *     alert: string
     * }
     */
    public function getMessagesArray(): array
    {
        $messages = [];
        foreach ($this->getMessages() as $message) {
            $messages[] = [
                'text'  => $message->getMessage(),
                'level' => $message->getLevel()->flash(),
                'alert' => $message->getLevel()->alert(),
            ];
        }

        return $messages;
    }

    /**
     * @param Message[] $messages
     */
    public function addMessages(iterable $messages): self
    {
        foreach ($messages as $message) {
            $this->addMessage($message);
        }

        return $this;
    }
}
