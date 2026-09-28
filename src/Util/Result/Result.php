<?php

namespace HBM\BasicsBundle\Util\Result;

class Result
{
    public function __construct(?bool $return = null)
    {
        if (method_exists($this, 'setReturn')) {
            $this->setReturn($return);
        }
        if (method_exists($this, 'initMessages')) {
            $this->initMessages();
        }
        if (method_exists($this, 'initNotices')) {
            $this->initNotices();
        }
    }

}
