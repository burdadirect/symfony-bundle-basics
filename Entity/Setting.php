<?php

namespace HBM\Basics\Entity;

use HBM\Basics\Util\Data\SettingVarType;

class Setting extends Entity {

  /****************************************************************************/
  /* PROPERTIES                                                               */
  /****************************************************************************/

  /**
   * @var string
   */
  private $varType;

  /**
   * @var string
   */
  private $varNature;

  /**
   * @var string
   */
  private $varKey;

  /**
   * @var float
   */
  private $varValue;

  /**
   * @var bool
   */
  private $editable = FALSE;

  /**
   * @var string
   */
  private $notice;

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
   * @param float $varValue
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
   * @return float|int|string|bool
   */
  public function getVarValue() {
    return $this->varValue;
  }

  /**
   * Set editable
   *
   * @param boolean $editable
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
      return (boolean) $this->getVarValue();
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
