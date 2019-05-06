<?php

namespace HBM\BasicsBundle\Security\Events;

use \Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class ImplicitLoginEvent
 *
 * This class is to determine an implicit (programatically) login event.
 */
class ImplicitLoginEvent extends InteractiveLoginEvent  {
}
