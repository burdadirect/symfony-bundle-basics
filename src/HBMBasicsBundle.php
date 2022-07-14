<?php

namespace HBM\BasicsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HBMBasicsBundle extends Bundle {

  public function getPath(): string {
    return \dirname(__DIR__);
  }

}
