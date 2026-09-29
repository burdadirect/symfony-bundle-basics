<?php

namespace HBM\BasicsBundle\Util\Result\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use HBM\BasicsBundle\Entity\Interfaces\NoticeInterface;

trait ResultNoticesTrait
{
    /** @var Collection<array-key, NoticeInterface> */
    protected Collection $notices;

    protected function initNotices(): void
    {
        $this->notices = new ArrayCollection();
    }

    public function addNotice(NoticeInterface $notice): static
    {
        if (!$this->notices->contains($notice)) {
            $this->notices->add($notice);
        }

        return $this;
    }

    public function removeNotice(NoticeInterface $notice): static
    {
        if ($this->notices->contains($notice)) {
            $this->notices->removeElement($notice);
        }

        return $this;
    }

    /**
     * @return Collection<array-key, NoticeInterface>
     */
    public function getNotices(): Collection
    {
        return $this->notices;
    }

}
