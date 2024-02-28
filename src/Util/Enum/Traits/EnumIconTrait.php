<?php

namespace HBM\BasicsBundle\Util\Enum\Traits;

trait EnumIconTrait
{
    abstract public function field(string $field, mixed $default = null): mixed;

    public function icon(): string
    {
        return $this->field('icon', '');
    }

    public function iconColor(): string
    {
        return $this->field('iconColor', '');
    }

    public function iconTitle(): string
    {
        return $this->field('iconTitle', '');
    }
}
