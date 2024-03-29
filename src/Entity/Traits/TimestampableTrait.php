<?php

namespace HBM\BasicsBundle\Entity\Traits;

trait TimestampableTrait
{
    /** @var \DateTime */
    protected $created;

    /** @var \DateTime */
    protected $modified;

    public array $updateTimestamps = [
      'created' => true,
      'modified' => true,
    ];

    /**
     * Set created
     *
     * @param \DateTime|string $created
     *
     * @throws \Exception
     *
     * @return self
     */
    public function setCreated($created)
    {
        if (is_string($created)) {
            $created = new \DateTime($created);
        }

        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     */
    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    /**
     * Set modified
     *
     * @param \DateTime|string $modified
     *
     * @throws \Exception
     *
     * @return self
     */
    public function setModified($modified)
    {
        if (is_string($modified)) {
            $modified = new \DateTime($modified);
        }

        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     */
    public function getModified(): ?\DateTime
    {
        return $this->modified;
    }

    /**
     * Lifecycle callback
     *
     * @throws \Exception
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
