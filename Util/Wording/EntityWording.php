<?php

namespace HBM\BasicsBundle\Util\Wording;

class EntityWording {

  /**
   * @var string
   */
  protected $type;


  /**
   * @var string
   */
  protected $id;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $nominative;

  /**
   * @var string
   */
  protected $function;

  public function __construct($type, $name = NULL, $nominative = 'der/die/das', $function = NULL) {
    $this->type = $type;
    $this->name = $name;
    $this->nominative = $nominative;
    $this->function = $function;
  }

  /**
   * Set type.
   *
   * @param string $type
   *
   * @return self
   */
  public function setType(string $type = NULL) : self {
    $this->type = $type;

    return $this;
  }

  /**
   * Get type.
   *
   * @return string|null
   */
  public function getType() : ?string {
    return $this->type;
  }

  /**
   * Set nominative.
   *
   * @param string $nominative
   *
   * @return self
   */
  public function setNominative(string $nominative = NULL) : self {
    $this->nominative = $nominative;

    return $this;
  }

  /**
   * Get nominative.
   *
   * @return string|null
   */
  public function getNominative($ucfirst = NULL) : ?string {
    if ($ucfirst === TRUE) {
      return ucfirst($this->nominative);
    } elseif ($ucfirst === FALSE) {
      return lcfirst($this->nominative);
    } else {
      return $this->nominative;
    }
  }

  /**
   * Set function.
   *
   * @param string $function
   *
   * @return self
   */
  public function setFunction(string $function = NULL) : self {
    $this->function = $function;

    return $this;
  }

  /**
   * Get function.
   *
   * @return string|null
   */
  public function getFunction() : ?string {
    return $this->function;
  }

  /**
   * Set id.
   *
   * @param string $id
   *
   * @return self
   */
  public function setId(string $id = NULL) : self {
    $this->id = $id;

    return $this;
  }

  /**
   * Get id.
   *
   * @return string|null
   */
  public function getId() : ?string {
    return $this->id;
  }

  /**
   * Set entityName.
   *
   * @param string $entityName
   *
   * @return self
   */
  public function setName(string $name = NULL) : self {
    $this->name = $name;

    return $this;
  }

  /**
   * Get entityName.
   *
   * @return string|null
   */
  public function getName() : ?string {
    return $this->name;
  }

  /**
   * @param null $object
   *
   * @return string|null
   */
  public function assignName($object = NULL) : self {
    $this->setName($this->extractName($object));

    return $this;
  }

  /**
   * @param null $object
   *
   * @return string|null
   */
  public function extractName($object = NULL) : ?string {
    $name = NULL;
    if ($this->getFunction() && $object) {
      try {
        $name = $object->{$this->getFunction()}();
      } catch (\Exception $exception) {
      }
    }

    return $name;
  }

  /**
   * Creates an entity label.
   *
   * @param string $format
   * @param bool|NULL $ucfirst
   *
   * @return string
   */
  private function label($format, $ucfirst = FALSE) : string {
    $idPart = $this->getId() ? ' [#'.$this->getId().']' : '';

    return sprintf($format, $this->getNominative($ucfirst), $this->getType(), $idPart);
  }

  /**
   * @param null $class
   * @param bool $ucfirst
   *
   * @return string
   */
  public function labelHtml($class = NULL, $ucfirst = FALSE) : string {
    $classPart = $class ? ' class="'.$class.'"' : '';
    $namePart = $this->getName() ? ' <em'.$classPart.'>'.$this->getName().'</em>' : '';

    return $this->label('%s <strong>%s'.$namePart.'%s</strong>', $ucfirst);
  }

  /**
   * @param bool $ucfirst
   *
   * @return string
   */
  public function labelText($ucfirst = FALSE) : string {
    $namePart = $this->getName() ? ' "'.$this->getName().'"' : '';

    return $this->label('%s %s'.$namePart.'%s', $ucfirst);
  }

}
