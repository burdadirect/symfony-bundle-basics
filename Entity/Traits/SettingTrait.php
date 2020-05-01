<?php

namespace HBM\BasicsBundle\Entity\Traits;

use HBM\BasicsBundle\Util\Data\SettingVarType;

trait SettingTrait {

  /****************************************************************************/
  /* PROPERTIES                                                               */
  /****************************************************************************/

  /**
   * @var string
   */
  protected $varType;

  /**
   * @var string
   */
  protected $varNature;

  /**
   * @var string
   */
  protected $varKey;

  /**
   * @var mixed
   */
  protected $varValue;

  /**
   * @var bool
   */
  protected $editable = FALSE;

  /**
   * @var bool
   */
  private $previewable = TRUE;

  /**
   * @var string
   */
  protected $notice;

  /****************************************************************************/
  /* CONSTRUCTOR / GETTER / SETTER                                            */
  /****************************************************************************/

  /**
   * Set varType
   *
   * @param string $varType
   *
   * @return self
   */
  public function setVarType($varType) : self {
    $this->varType = $varType;

    return $this;
  }

  /**
   * Get varType
   *
   * @return string|null
   */
  public function getVarType() : ?string {
    return $this->varType;
  }

  /**
   * Set varNature
   *
   * @param string $varNature
   *
   * @return self
   */
  public function setVarNature($varNature) : self {
    $this->varNature = $varNature;

    return $this;
  }

  /**
   * Get varNature
   *
   * @return string|null
   */
  public function getVarNature() : ?string {
    return $this->varNature;
  }

  /**
   * Set varKey
   *
   * @param string $varKey
   *
   * @return self
   */
  public function setVarKey($varKey) : self {
    $this->varKey = $varKey;

    return $this;
  }

  /**
   * Get varKey
   *
   * @return string
   */
  public function getVarKey() : ?string {
    return $this->varKey;
  }

  /**
   * Set varValue
   *
   * @param mixed $varValue
   *
   * @return self
   */
  public function setVarValue($varValue) : self {
    $this->varValue = $varValue;

    return $this;
  }

  /**
   * Get varValue
   *
   * @return mixed|float|int|string|bool
   */
  public function getVarValue() {
    return $this->varValue;
  }

  /**
   * Set editable.
   *
   * @param bool $editable
   *
   * @return self
   */
  public function setEditable($editable) : self {
    $this->editable = $editable;

    return $this;
  }

  /**
   * Get editable
   *
   * @return bool|null
   */
  public function getEditable() : ?bool {
    return $this->editable;
  }

  /**
   * Set previewable.
   *
   * @param bool $previewable
   *
   * @return self
   */
  public function setPreviewable(bool $previewable = NULL) : self {
    $this->previewable = $previewable;

    return $this;
  }

  /**
   * Get previewable.
   *
   * @return bool|null
   */
  public function getPreviewable() : ?bool {
    return $this->previewable;
  }

  /**
   * Set notice
   *
   * @param string $notice
   *
   * @return self
   */
  public function setNotice($notice) : self {
    $this->notice = $notice;

    return $this;
  }

  /**
   * Get notice
   *
   * @return string|null
   */
  public function getNotice() : ?string {
    return $this->notice;
  }

  /****************************************************************************/
  /* CUSTOM                                                                   */
  /****************************************************************************/

  /**
   * @param string|null $default
   * @return string|null
   */
  public function getVarTypeLabel(string $default = NULL) : ?string {
    return SettingVarType::label($this->getVarType(), $default);
  }

  /**
   * @return mixed
   */
  public function getVarValueParsed() {
    if ($this->getVarType() === SettingVarType::INT) {
      return (int) $this->getVarValue();
    }

    if ($this->getVarType() === SettingVarType::FLOAT) {
      return (float) $this->getVarValue();
    }

    if ($this->getVarType() === SettingVarType::BOOLEAN) {
      return (bool) $this->getVarValue();
    }

    if ($this->getVarType() === SettingVarType::CSV) {
      $lines = explode("\n", trim($this->getVarValue()));
      $rows = [];
      foreach ($lines as $line) {
        $rows[] = array_map('trim', explode(';', $line));
      }
      return $rows;
    }

    if ($this->getVarType() === SettingVarType::JSON) {
      return json_decode($this->getVarValue(), TRUE);
    }

    return $this->getVarValue();
  }

}
