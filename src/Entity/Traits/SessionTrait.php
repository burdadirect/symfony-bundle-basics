<?php

namespace HBM\BasicsBundle\Entity\Traits;

trait SessionTrait
{
    /* PROPERTIES */

    protected ?string $sessionId = null;
    protected ?string $sessionData = null;
    protected ?int $sessionTime = null;
    protected ?int $sessionLifetime = null;

    /* CONSTRUCTOR / GETTER / SETTER */

    public function setSessionId(?string $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionData(?string $sessionData): self
    {
        $this->sessionData = $sessionData;

        return $this;
    }

    public function getSessionData(): ?string
    {
        return $this->sessionData;
    }

    public function setSessionTime(?int $sessionTime): self
    {
        $this->sessionTime = $sessionTime;

        return $this;
    }

    public function getSessionTime(): ?int
    {
        return $this->sessionTime;
    }

    public function setSessionLifetime(?int $sessionLifetime): self
    {
        $this->sessionLifetime = $sessionLifetime;

        return $this;
    }

    public function getSessionLifetime(): ?int
    {
        return $this->sessionLifetime;
    }
}
