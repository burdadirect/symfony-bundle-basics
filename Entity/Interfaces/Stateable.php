<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

interface Stateable extends Addressable {

  /**
   * Set state.
   *
   * @param int $state
   *
   * @return self
   */
  public function setState(int $state);

  /**
   * Get state.
   *
   * @return int
   */
  public function getState() : int;

  /****************************************************************************/
  /* CUSTOM                                                                   */
  /****************************************************************************/

  /**
   * @return bool
   */
  public function isActive() : bool;

  /**
   * @return bool
   */
  public function isPending() : bool;

  /**
   * @return bool
   */
  public function isBlocked() : bool;

}
