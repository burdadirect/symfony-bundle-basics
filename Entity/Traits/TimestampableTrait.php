<?php

namespace HBM\BasicsBundle\Entity\Traits;

trait TimestampableTrait {

  /**
   * @var \DateTime
   */
  protected $created;

  /**
   * @var \DateTime
   */
  protected $modified;

  /**
   * Set created
   *
   * @param \DateTime|string $created
   *
   * @return self
   *
   * @throws \Exception
   */
  public function setCreated($created) : self {
    if (is_string($created)) {
      $created = new \DateTime($created);
    }

    $this->created = $created;

    return $this;
  }

  /**
   * Get created
   *
   * @return \DateTime
   */
  public function getCreated() : ?\DateTime {
    return $this->created;
  }

  /**
   * Set modified
   *
   * @param \DateTime|string $modified
   *
   * @return self
   *
   * @throws \Exception
   */
  public function setModified($modified) : self {
    if (is_string($modified)) {
      $modified = new \DateTime($modified);
    }

    $this->modified = $modified;

    return $this;
  }

  /**
   * Get modified
   *
   * @return \DateTime
   */
  public function getModified() : ?\DateTime {
    return $this->modified;
  }

  /**
   * Lifecycle callback
   *
   * @throws \Exception
   */
  public function updateTimestamps() : void {
    $this->setModified(new \DateTime('now'));

    if($this->getCreated() === null) {
      $this->setCreated(new \DateTime('now'));
    }
  }

}
