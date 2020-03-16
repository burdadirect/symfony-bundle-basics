<?php

namespace HBM\BasicsBundle\Entity\Traits;

use HBM\BasicsBundle\Util\Data\State;

trait StateableTrait {

  /**
   * Set state.
   *
   * @param int $state
   *
   * @return self
   */
  public function setState(int $state) {
    $this->state = $state;

    return $this;
  }

  /**
   * Get state.
   *
   * @return int
   */
  public function getState() : int {
    return $this->state;
  }

  /****************************************************************************/
  /* CUSTOM                                                                   */
  /****************************************************************************/

  public function isActive() : bool {
    return $this->getState() === State::ACTIVE;
  }

  public function isPending() : bool {
    return $this->getState() === State::PENDING;
  }

  public function isReview() : bool {
    return $this->getState() === State::REVIEW;
  }

  public function isBlocked() : bool {
    return $this->getState() === State::BLOCKED;
  }

}
