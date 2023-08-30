<?php

namespace HBM\BasicsBundle\Util\Wording;

class EntityWording
{
    protected ?string $id = null;

    protected ?string $type;

    protected ?string $name;

    protected ?string $nominative;

    protected ?string $function;

    public function __construct(?string $type, string $name = null, ?string $nominative = 'der/die/das', string $function = null)
    {
        $this->type       = $type;
        $this->name       = $name;
        $this->nominative = $nominative;
        $this->function   = $function;
    }

    /**
     * Set type.
     */
    public function setType(string $type = null): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set nominative.
     */
    public function setNominative(string $nominative = null): self
    {
        $this->nominative = $nominative;

        return $this;
    }

    /**
     * Get nominative.
     */
    public function getNominative(bool $ucfirst = null): ?string
    {
        if ($ucfirst === true) {
            return ucfirst($this->nominative);
        }

        if ($ucfirst === false) {
            return lcfirst($this->nominative);
        }

        return $this->nominative;
    }

    /**
     * Set function.
     */
    public function setFunction(string $function = null): self
    {
        $this->function = $function;

        return $this;
    }

    /**
     * Get function.
     */
    public function getFunction(): ?string
    {
        return $this->function;
    }

    /**
     * Set id.
     */
    public function setId(string $id = null): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id.
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set entityName.
     */
    public function setName(string $name = null): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get entityName.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null $object
     */
    public function assignName($object = null): self
    {
        $this->setName($this->extractName($object));

        return $this;
    }

    /**
     * @param null $object
     */
    public function extractName($object = null): ?string
    {
        $name = null;

        if ($object && $this->getFunction()) {
            try {
                $name = $object->{$this->getFunction()}();
            } catch (\Exception $exception) {
            }
        }

        return $name;
    }

    private function escapeTextForFormatString($text)
    {
        return str_replace('%', '%%', $text);
    }

    /**
     * Creates an entity label.
     *
     * @param null|bool $ucfirst
     */
    private function label(string $format, bool $ucfirst = false): string
    {
        $idPart = $this->getId() ? ' [#' . $this->getId() . ']' : '';

        $nominativePart = $this->getNominative($ucfirst);

        if ($nominativePart) {
            $nominativePart .= ' ';
        }

        return sprintf($format, $nominativePart, $this->getType(), $idPart);
    }

    /**
     * @param null $class
     */
    public function labelHtml($class = null, bool $ucfirst = false, bool $htmlentities = true): string
    {
        $classPart = $class ? ' class="' . $class . '"' : '';
        $namePart  = $this->getName() ? ' <em' . $classPart . '>' . ($htmlentities ? htmlentities($this->getName()) : $this->getName()) . '</em>' : '';

        return $this->label('%s<strong>%s' . $this->escapeTextForFormatString($namePart) . '%s</strong>', $ucfirst);
    }

    public function labelText(bool $ucfirst = false): string
    {
        $namePart = $this->getName() ? ' "' . strip_tags($this->getName()) . '"' : '';

        return $this->label('%s%s' . $this->escapeTextForFormatString($namePart) . '%s', $ucfirst);
    }

    public function confirmDeletionTitle(bool $htmlentities = true): string
    {
        return 'Bitte bestätigen Sie, dass ' . $this->labelHtml('text-primary', false, $htmlentities) . ' gelöscht werden soll.';
    }

    public function confirmDeletionSuccess(array $unlinkedFiles = [], bool $htmlentities = true): string
    {
        $entityNominative = $this->labelHtml(null, true, $htmlentities);

        $message = $entityNominative . ' wurde gelöscht.';

        if (\count($unlinkedFiles) > 0) {
            $message = $entityNominative . ' und die zugehörige(n) ' . \count($unlinkedFiles) . ' Datei(en) wurden gelöscht.';
        }

        return $message;
    }
}
