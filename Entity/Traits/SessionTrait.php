<?php

namespace HBM\BasicsBundle\Entity\Traits;

trait SessionTrait {

  /****************************************************************************/
  /* PROPERTIES                                                               */
  /****************************************************************************/

  /**
   * @var string
   */
  protected $sessionId;

  /**
   * @var string
   */
  protected $sessionData;

  /**
   * @var int
   */
  protected $sessionTime;

  /**
   * @var int
   */
  protected $sessionLifetime;

  /****************************************************************************/
  /* CONSTRUCTOR / GETTER / SETTER                                            */
  /****************************************************************************/

  /**
   * Set sessionId
   *
   * @param string $sessionId
   *
   * @return self
   */
  public function setSessionId($sessionId) : self {
    $this->sessionId = $sessionId;

    return $this;
  }

  /**
   * Get sessionId
   *
   * @return string
   */
  public function getSessionId() : string {
    return $this->sessionId;
  }

  /**
   * Set sessionData
   *
   * @param string $sessionData
   *
   * @return self
   */
  public function setSessionData($sessionData) : self {
    $this->sessionData = $sessionData;

    return $this;
  }

  /**
   * Get sessionData
   *
   * @return string
   */
  public function getSessionData() : string {
    return $this->sessionData;
  }

  /**
   * Set sessionTime
   *
   * @param int $sessionTime
   *
   * @return self
   */
  public function setSessionTime($sessionTime) : self {
    $this->sessionTime = $sessionTime;

    return $this;
  }

  /**
   * Get sessionTime
   *
   * @return int
   */
  public function getSessionTime() : int {
    return $this->sessionTime;
  }

  /**
   * Set sessionLifetime
   *
   * @param int $sessionLifetime
   *
   * @return self
   */
  public function setSessionLifetime($sessionLifetime) : self {
    $this->sessionLifetime = $sessionLifetime;

    return $this;
  }

  /**
   * Get sessionLifetime
   *
   * @return int
   */
  public function getSessionLifetime() : int {
    return $this->sessionLifetime;
  }

}
