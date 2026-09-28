<?php

namespace HBM\BasicsBundle\Entity\Traits;

use HBM\BasicsBundle\Util\Enum\SettingVarType;

trait SettingTrait
{
    /* PROPERTIES */

    protected ?SettingVarType $varType = null;

    protected ?string $varNature = null;
    protected ?string $varKey = null;
    protected mixed $varValue = null;

    protected bool $editable = false;
    protected bool $previewable = true;

    protected ?string $notice = null;

    /* CONSTRUCTOR / GETTER / SETTER */

    public function getVarType(): ?SettingVarType
    {
        return $this->varType;
    }

    public function setVarType(?SettingVarType $varType): self
    {
        $this->varType = $varType;

        return $this;
    }

    public function getVarNature(): ?string
    {
        return $this->varNature;
    }

    public function setVarNature(?string $varNature): self
    {
        $this->varNature = $varNature;

        return $this;
    }

    public function getVarKey(): ?string
    {
        return $this->varKey;
    }

    public function setVarKey(?string $varKey): self
    {
        $this->varKey = $varKey;

        return $this;
    }

    public function getVarValue(): mixed
    {
        return $this->varValue;
    }

    public function setVarValue(mixed $varValue): self
    {
        $this->varValue = $varValue;

        return $this;
    }

    public function getEditable(): bool
    {
        return $this->editable;
    }

    public function setEditable(bool $editable): self
    {
        $this->editable = $editable;

        return $this;
    }

    public function getPreviewable(): bool
    {
        return $this->previewable;
    }

    public function setPreviewable(bool $previewable): self
    {
        $this->previewable = $previewable;

        return $this;
    }

    public function getNotice(): ?string
    {
        return $this->notice;
    }

    public function setNotice(?string $notice): self
    {
        $this->notice = $notice;

        return $this;
    }

    /* CUSTOM */

    public function getVarValueParsed()
    {
        return $this->getVarValueParsedInternal($this->getVarType(), $this->getVarValue());
    }

    protected function getVarValueParsedInternal(SettingVarType $varType, ?string $varValue): mixed
    {
        if ($varType === SettingVarType::INT) {
            return (int) $varValue;
        }

        if ($varType === SettingVarType::FLOAT) {
            return (float) $varValue;
        }

        if ($varType === SettingVarType::BOOLEAN) {
            return (bool) $varValue;
        }

        if ($varType === SettingVarType::CSV) {
            $lines = explode("\n", trim($varValue));
            $rows  = [];
            foreach ($lines as $line) {
                $rows[] = array_map('trim', explode(';', $line));
            }

            return $rows;
        }

        if ($varType === SettingVarType::JSON) {
            return json_decode($varValue, true);
        }

        return $varValue;
    }

}
