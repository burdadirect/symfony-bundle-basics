<?php

namespace HBM\BasicsBundle\Util\Result\Interfaces;

use Doctrine\Common\Collections\Collection;
use HBM\BasicsBundle\Util\Result\Message;

interface ResultMessagesInterface
{
    /**
     * @return Collection<array-key, Message>
     */
    public function getMessages(): Collection;
}
