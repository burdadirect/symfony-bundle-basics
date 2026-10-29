<?php

namespace HBM\BasicsBundle\Entity\Repository\Interfaces;

use HBM\BasicsBundle\Entity\Interfaces\SettingInterface;
use HBM\BasicsBundle\Util\Enum\SettingVarType;

interface SettingRepositoryInterface
{
    public function getVarValueParsed(string $key, ?string $nature = null, mixed $default = null, ?array $orderBy = null): mixed;

    public function findOrCreateOne(string $key, string $nature = 'misc', SettingVarType $type = SettingVarType::STRING, mixed $initialValue = null, bool $editable = true, bool $previewable = true, bool $persistAndFlush = false): SettingInterface;
}
