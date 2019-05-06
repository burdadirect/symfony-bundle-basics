<?php

namespace HBM\Basics\Service;

abstract class AbstractService {

  /**
   * @var AbstractServiceHelper
   */
  protected $sh;

  /**
   * @var AbstractDoctrineHelper
   */
  protected $dh;

  public function __construct(AbstractServiceHelper $serviceHelper, AbstractDoctrineHelper $doctrineHelper) {
    $this->sh = $serviceHelper;
    $this->dh = $doctrineHelper;
  }

}
