<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

interface NoticeInterface extends Addressable
{
    /**
     * Get title.
     */
    public function getTitle(): ?string;

    /**
     * Get message.
     */
    public function getMessage(): ?string;

    /**
     * Get alert level.
     */
    public function getAlertLevel(): string;
}
