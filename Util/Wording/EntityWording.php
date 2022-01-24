<?php

namespace HBM\BasicsBundle\Util\Wording;

class EntityWording {

  protected ?string $id = null;

  protected ?string $type;

  protected ?string $name;

  protected ?string $nominative;

  protected ?string $function;

  public function __construct(?string $type, ?string $name = NULL, ?string $nominative = 'der/die/das', ?string $function = NULL) {
    $this->type = $type;
    $this->name = $name;
    $this->nominative = $nominative;
    $this->function = $function;
  }

  /**
   * Set type.
   *
   * @param string|null $type
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
   * @param string|null $nominative
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
   * @param bool|null $ucfirst
   *
   * @return string|null
   */
  public function getNominative(bool $ucfirst = NULL) : ?string {
    if ($ucfirst === TRUE) {
      return ucfirst($this->nominative);
    }
    if ($ucfirst === FALSE) {
      return lcfirst($this->nominative);
    }

    return $this->nominative;
  }

  /**
   * Set function.
   *
   * @param string|null $function
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
   * @param string|null $id
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
   * @param string|null $name
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
   * @return self
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
    if ($object && $this->getFunction()) {
      try {
        $name = $object->{$this->getFunction()}();
      } catch (\Exception $exception) {
      }
    }

    return $name;
  }

  private function escapeTextForFormatString($text) {
    return str_replace('%', '%%', $text);
  }

  /**
   * Creates an entity label.
   *
   * @param string $format
   * @param bool|NULL $ucfirst
   *
   * @return string
   */
  private function label(string $format, bool $ucfirst = FALSE) : string {
    $idPart = $this->getId() ? ' [#'.$this->getId().']' : '';

    $nominativePart = $this->getNominative($ucfirst);
    if ($nominativePart) {
      $nominativePart .= ' ';
    }

    return sprintf($format, $nominativePart, $this->getType(), $idPart);
  }

  /**
   * @param null $class
   * @param bool $ucfirst
   * @param bool $htmlentities
   * 
   * @return string
   */
  public function labelHtml($class = NULL, bool $ucfirst = FALSE, bool $htmlentities = TRUE) : string {
    $classPart = $class ? ' class="'.$class.'"' : '';
    $namePart = $this->getName() ? ' <em'.$classPart.'>'.($htmlentities ? htmlentities($this->getName()) : $this->getName()).'</em>' : '';

    return $this->label('%s<strong>%s'.$this->escapeTextForFormatString($namePart).'%s</strong>', $ucfirst);
  }

  /**
   * @param bool $ucfirst
   *
   * @return string
   */
  public function labelText(bool $ucfirst = FALSE) : string {
    $namePart = $this->getName() ? ' "'.strip_tags($this->getName()).'"' : '';

    return $this->label('%s%s'.$this->escapeTextForFormatString($namePart).'%s', $ucfirst);
  }

  /****************************************************************************/

  public function confirmDeletionTitle(bool $htmlentities = TRUE) : string {
    return 'Bitte bestätigen Sie, dass '.$this->labelHtml('text-primary', FALSE, $htmlentities).' gelöscht werden soll.';
  }

  public function confirmDeletionSuccess(array $unlinkedFiles = [],bool $htmlentities = TRUE) : string {
    $entityNominative = $this->labelHtml(NULL, TRUE, $htmlentities);

    $message = $entityNominative.' wurde gelöscht.';
    if (\count($unlinkedFiles) > 0) {
      $message = $entityNominative.' und die zugehörige(n) '.\count($unlinkedFiles).' Datei(en) wurden gelöscht.';
    }

    return $message;
  }

}
