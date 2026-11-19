<?php

namespace HBM\BasicsBundle\Util;

class CommandExecutionLimit
{
    /**
     * @param bool|int $exitCode int for specific exit code, true for any exit code, false for no exit code
     */
    public function __construct(
        private int $max,
        private ?\DateInterval $interval = null,
        private int|bool $exitCode = true,
    ) {
    }

    public function getMax(): int
    {
        return $this->max;
    }

    public function setMax(int $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function getInterval(): ?\DateInterval
    {
        return $this->interval;
    }

    public function setInterval(?\DateInterval $interval): self
    {
        $this->interval = $interval;

        return $this;
    }

    public function getExitCode(): bool|int
    {
        return $this->exitCode;
    }

    public function setExitCode(bool|int $exitCode): self
    {
        $this->exitCode = $exitCode;

        return $this;
    }
}
