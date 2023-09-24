<?php

namespace HBM\BasicsBundle\Util\ConfirmMessage;

use Doctrine\Common\Collections\Collection;
use HBM\BasicsBundle\Entity\AbstractEntity;
use Symfony\Component\Routing\RouterInterface;

class ConfirmMessage
{
    public const MODE_DELETE   = 'delete';
    public const MODE_NULLIFY  = 'nullify';
    public const MODE_REASSIGN = 'reassign';
    public const MODE_RESOLVE  = 'resolve';

    /** @var AbstractEntity[]|array|Collection */
    private $items;

    /** @var string */
    private $wording;

    /** @var string */
    private $headline = '<p>An das Objekt sind folgende <strong>%s</strong> geknüpft:</p>';

    /** @var string */
    private $message;

    /** @var string */
    private $mode;

    private $text;

    private $title;

    private $icon;

    private $route;

    private $children;

    private $discard;

    public function __construct($items = [], string $wording = null, $mode = null)
    {
        $this->items   = $items ?: [];
        $this->wording = $wording;
        $this->mode    = $mode;
    }

    /**
     * Set items.
     *
     * @param AbstractEntity[]|array|Collection $items
     */
    public function setItems($items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Get items.
     *
     * @return AbstractEntity[]|array|Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set wording.
     */
    public function setWording(?string $wording): self
    {
        $this->wording = $wording;

        return $this;
    }

    /**
     * Get wording.
     */
    public function getWording(): ?string
    {
        return $this->wording;
    }

    /**
     * Set headline.
     */
    public function setHeadline(?string $headline): self
    {
        $this->headline = $headline;

        return $this;
    }

    /**
     * Get headline.
     */
    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    /**
     * Set message.
     */
    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Set mode.
     */
    public function setMode(?string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Get mode.
     */
    public function getMode(): ?string
    {
        return $this->mode;
    }

    /**
     * Set text.
     */
    public function setText($text = null): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text.
     *
     * @return null|mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set title.
     */
    public function setTitle($title = null): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return null|mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set route.
     */
    public function setRoute($route = null): self
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route.
     *
     * @return null|mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set icon.
     */
    public function setIcon($icon = null): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon.
     *
     * @return null|mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set children.
     */
    public function setChildren($children): self
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get children.
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set discard.
     */
    public function setDiscard($discard = null): self
    {
        $this->discard = $discard;

        return $this;
    }

    /**
     * Get discard.
     *
     * @return null|mixed
     */
    public function getDiscard()
    {
        return $this->discard;
    }

    public function evalId(AbstractEntity $item): string
    {
        return '' . $item->getId();
    }

    public function evalDiscard(AbstractEntity $item): bool
    {
        if ($this->discard instanceof \Closure) {
            return (bool) call_user_func($this->discard, $item);
        }

        return false;
    }

    public function evalText(AbstractEntity $item): ?string
    {
        if ($this->text instanceof \Closure) {
            return call_user_func($this->text, $item);
        }

        if ($this->text !== null) {
            if (method_exists($item, $this->text)) {
                return call_user_func([$item, $this->text]);
            }

            return $this->text;
        }

        return null;
    }

    public function evalTitle(AbstractEntity $item): ?string
    {
        if ($this->title instanceof \Closure) {
            return call_user_func($this->title, $item);
        }

        if ($this->title !== null) {
            if (method_exists($item, $this->title)) {
                return call_user_func([$item, $this->title]);
            }

            return $this->title;
        }

        return null;
    }

    public function evalIcon(AbstractEntity $item): ?string
    {
        if ($this->icon instanceof \Closure) {
            return call_user_func($this->icon, $item);
        }

        if ($this->icon !== null) {
            return $this->icon;
        }

        return null;
    }

    public function evalUrl(AbstractEntity $item, RouterInterface $router = null): ?string
    {
        if ($this->route instanceof \Closure) {
            return call_user_func($this->route, $item, $router);
        }

        if ($this->route !== null) {
            $first = $this->route[0] ?? '';

            if ($first === '/') {
                return $this->route;
            }

            if ($router) {
                try {
                    return $router->generate($this->route, ['id' => $item->getId()]);
                } catch (\Exception $e) {
                }
            }
        }

        return null;
    }

    public function evalChildren(AbstractEntity $item): array
    {
        if ($this->children instanceof \Closure) {
            return call_user_func($this->children, $item);
        }

        return [];
    }

    public function render(AbstractEntity $item, RouterInterface $router = null): string
    {
        $id    = $this->evalId($item);
        $url   = $this->evalUrl($item, $router);
        $text  = $this->evalText($item);
        $icon  = $this->evalIcon($item);
        $title = $this->evalTitle($item);

        if ($url) {
            return '<a href="' . $url . '" title="' . strip_tags($title ?: $text) . '">' . $id . '</a>: ' . $text;
        }

        return $id . ': ' . $text;
    }
}
