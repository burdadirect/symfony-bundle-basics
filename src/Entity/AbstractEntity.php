<?php

namespace HBM\BasicsBundle\Entity;

use HBM\BasicsBundle\Entity\Interfaces\Addressable;
use HBM\BasicsBundle\Entity\Interfaces\Timestampable;
use HBM\BasicsBundle\Entity\Interfaces\Uuidable;
use HBM\BasicsBundle\Entity\Traits\TimestampableTrait;
use HBM\BasicsBundle\Entity\Traits\UuidableTrait;

abstract class AbstractEntity implements Addressable, Uuidable, Timestampable
{
    use UuidableTrait;
    use TimestampableTrait;

    /** @var int */
    protected $id;

    /**
     * Construct entity and set UUID.
     */
    public function __construct()
    {
        $this->setUuid();
    }

    /**
     * Get id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets a zero padded id.
     */
    public function getIdPadded(): string
    {
        return str_pad($this->getId(), 11, '0', STR_PAD_LEFT);
    }

    /**
     * Get the id with the FQCN prefixed.
     */
    public function getIdFQCN(): string
    {
        return static::class . '#' . $this->getId();
    }
}
