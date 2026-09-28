<?php

namespace HBM\BasicsBundle\Util\Result\Interfaces;

use Doctrine\Common\Collections\Collection;
use HBM\BasicsBundle\Entity\Interfaces\NoticeInterface;

interface ResultNoticesInterface
{
    /**
     * @return Collection<array-key, NoticeInterface>
     */
    public function getNotices(): Collection;
}
