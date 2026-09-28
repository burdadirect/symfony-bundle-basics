<?php

namespace HBM\BasicsBundle\Entity\Traits;

trait TimestampableTrait
{
    protected ?\DateTime $created = null;
    protected ?\DateTime $modified = null;

    public array $updateTimestamps = [
        'created'  => true,
        'modified' => true,
    ];

    /**
     * @throws \DateMalformedStringException
     */
    public function setCreated(\DateTime|string|null $created): self
    {
        if (is_string($created)) {
            $created = new \DateTime($created);
        }

        $this->created = $created;

        return $this;
    }

    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function setModified(\DateTime|string|null $modified): self
    {
        if (is_string($modified)) {
            $modified = new \DateTime($modified);
        }

        $this->modified = $modified;

        return $this;
    }

    public function getModified(): ?\DateTime
    {
        return $this->modified;
    }

    /**
     * Lifecycle callback
     *
     * @throws \DateMalformedStringException
     */
    public function updateTimestamps(): void
    {
        if ($this->updateTimestamps['modified']) {
            $this->setModified(new \DateTime('now'));
        }

        if ($this->updateTimestamps['created'] && ($this->getCreated() === null)) {
            $this->setCreated(new \DateTime('now'));
        }
    }
}
