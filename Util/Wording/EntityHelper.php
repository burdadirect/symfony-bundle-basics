<?php

namespace HBM\BasicsBundle\Util\Wording;

abstract class EntityHelper {

  /**
   * @var string
   */
  protected $entityType;

  /**
   * @var string
   */
  protected $entityName;

  /**
   * @var string
   */
  protected $entityNominative;

  public function __construct($entityType, $entityName, $entityNominative = 'der/die/das') {
    $this->entityType = $entityType;
    $this->entityName = $entityName;
    $this->entityNominative = $entityNominative;
  }

  /**
   * Creates an entity label.
   *
   * @param string $class
   *
   * @return string
   */
  public function entityLabel($class = '') : string {
    return $this->entityNominative.' <strong>'.$this->entityType.($this->entityName?' <em class="'.$class.'">'.$this->entityName.'</em>':'').'</strong>';
  }

}
