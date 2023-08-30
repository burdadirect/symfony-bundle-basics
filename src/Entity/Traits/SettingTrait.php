<?php

namespace HBM\BasicsBundle\Entity\Traits;

use HBM\BasicsBundle\Util\Data\SettingVarType;

trait SettingTrait
{
    /* PROPERTIES */

    /** @var string */
    protected $varType;

    /** @var string */
    protected $varNature;

    /** @var string */
    protected $varKey;

    protected $varValue;

    /** @var bool */
    protected $editable = false;

    /** @var bool */
    private $previewable = true;

    /** @var string */
    protected $notice;

    /* CONSTRUCTOR / GETTER / SETTER */

    /**
     * Set varType
     *
     * @param string $varType
     */
    public function setVarType($varType): self
    {
        $this->varType = $varType;

        return $this;
    }

    /**
     * Get varType
     */
    public function getVarType(): ?string
    {
        return $this->varType;
    }

    /**
     * Set varNature
     *
     * @param string $varNature
     */
    public function setVarNature($varNature): self
    {
        $this->varNature = $varNature;

        return $this;
    }

    /**
     * Get varNature
     */
    public function getVarNature(): ?string
    {
        return $this->varNature;
    }

    /**
     * Set varKey
     *
     * @param string $varKey
     */
    public function setVarKey($varKey): self
    {
        $this->varKey = $varKey;

        return $this;
    }

    /**
     * Get varKey
     */
    public function getVarKey(): ?string
    {
        return $this->varKey;
    }

    /**
     * Set varValue
     */
    public function setVarValue($varValue): self
    {
        $this->varValue = $varValue;

        return $this;
    }

    /**
     * Get varValue
     *
     * @return bool|float|int|mixed|string
     */
    public function getVarValue()
    {
        return $this->varValue;
    }

    /**
     * Set editable.
     *
     * @param bool $editable
     */
    public function setEditable($editable): self
    {
        $this->editable = $editable;

        return $this;
    }

    /**
     * Get editable
     */
    public function getEditable(): ?bool
    {
        return $this->editable;
    }

    /**
     * Set previewable.
     */
    public function setPreviewable(bool $previewable = null): self
    {
        $this->previewable = $previewable;

        return $this;
    }

    /**
     * Get previewable.
     */
    public function getPreviewable(): ?bool
    {
        return $this->previewable;
    }

    /**
     * Set notice
     *
     * @param string $notice
     */
    public function setNotice($notice): self
    {
        $this->notice = $notice;

        return $this;
    }

    /**
     * Get notice
     */
    public function getNotice(): ?string
    {
        return $this->notice;
    }

    /* CUSTOM */

    public function getVarTypeLabel(string $default = null): ?string
    {
        return SettingVarType::label($this->getVarType(), $default);
    }

    public function getVarValueParsed()
    {
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
            $rows  = [];
            foreach ($lines as $line) {
                $rows[] = array_map('trim', explode(';', $line));
            }

            return $rows;
        }

        if ($this->getVarType() === SettingVarType::JSON) {
            return json_decode($this->getVarValue(), true);
        }

        return $this->getVarValue();
    }
}
