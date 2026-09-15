<?php

namespace HBM\BasicsBundle\Util;

class Total {

  private array $entries = [];

  public function note(mixed $value, string $key, ?string $row = null): mixed {
    if (!isset($this->entries[$key])) {
      $this->entries[$key] = [];
    }
    if ($row) {
      $this->entries[$key][$row] = $value;
    } else {
      $this->entries[$key][] = $value;
    }

    return $value;
  }

  public function num(string $key): int {
    return count($this->entries[$key] ?? []);
  }

  public function sum(string $key): int|float {
    return array_sum($this->entries[$key] ?? []);
  }

  public function reset(): self {
    $this->entries = [];

    return $this;
  }
}
