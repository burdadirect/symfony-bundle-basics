<?php

namespace HBM\BasicsBundle\Entity\Interfaces;

interface Timestampable extends Addressable {

  /**
   * Set created
   *
   * @param \DateTime|string $created
   *
   * @return self
   */
  public function setCreated($created);

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
  public function setModified($modified);

  /**
   * Get modified
   *
   * @return \DateTime|null
   */
  public function getModified() : ?\DateTime;

}
