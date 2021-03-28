<?php

namespace HBM\BasicsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class StringToArrayTransformer implements DataTransformerInterface {

  /**
   * @var string
   */
  protected $separator;

  /**
   * @var string
   */
  protected $glue;

  /**
   * StringToArrayTransformer constructor.
   *
   * @param string $separator
   * @param string|null $glue
   */
  public function __construct(string $separator, string $glue = NULL) {
    $this->separator = $separator;
    $this->glue = $glue ?: $separator;
  }

  /**
   * @param mixed $value
   *
   * @return mixed|string
   */
  public function transform($value) {
    return implode($this->glue, $value);
  }

  /**
   * @param mixed $value
   *
   * @return array|mixed
   */
  public function reverseTransform($value) {
    return array_map('trim', explode($this->separator, $value));
  }

}
