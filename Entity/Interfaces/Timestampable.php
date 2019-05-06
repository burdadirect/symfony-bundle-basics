<?php

namespace HBM\Basics\Entity\Interfaces;

interface Timestampable extends Addressable {

  /**
   * Set created
   *
   * @param \DateTime|string $created
   *
   * @return self
   */
  public function setCreated($created) : self;

  /**
   * Get created
   *
   * @return \DateTime|null
   */
  public function getCreated() : ?\DateTime;

  /**
   * Set modified
   *
   * @param \DateTime|string $modified
   *
   * @return self
   */
  public function setModified($modified) : self;

  /**
   * Get modified
   *
   * @return \DateTime|null
   */
  public function getModified() : ?\DateTime;

}
