<?php

namespace HBM\BasicsBundle\Service;

use HBM\BasicsBundle\Entity\AbstractEntity;

abstract class AbstractDeletionHelper {

  /**
   * @var AbstractServiceHelper
   */
  protected $sh;

  /**
   * @var AbstractDoctrineHelper
   */
  protected $dh;

  /**
   * @param AbstractEntity[] $items
   * @param string $headline
   * @param string|callable|NULL $callableRoute
   * @param string|callable|NULL $callableText
   * @param callable|NULL $callableDiscard
   * @param string|callable|NULL $callableIcon
   *
   * @return string
   */
  protected function assembleConfirmMessagesForRelatedItems($items, $headline, $callableRoute, $callableText, $callableDiscard = NULL, $callableIcon = NULL) : string {
    return $this->assembleConfirmMessages($items, 'An das Objekt sind folgende <strong>'.$headline.'</strong> geknüpft:', $callableRoute, $callableText, $callableDiscard, $callableIcon);
  }

  /**
   * @param AbstractEntity[] $items
   * @param string $headline
   * @param string|callable|NULL $callableRoute
   * @param string|callable|NULL $callableText
   * @param callable|NULL $callableDiscard
   * @param string|callable|NULL $callableIcon
   *
   * @return string
   */
  protected function assembleConfirmMessages($items, $headline, $callableRoute, $callableText, $callableDiscard = NULL, $callableIcon = NULL) : string {
    $message = '';
    if (\count($items) > 0) {
      $message .= '<p class="mb-1">'.$headline.'</p>';
      $message .= '<ul class="tree">';

      foreach ($items as $item) {
        // Should this item be discarded?
        $discard = FALSE;
        if ($callableDiscard instanceof \Closure) {
          $discard = $callableDiscard($item);
        }
        if ($discard) {
          continue;
        }

        // Get icon representation of the action for this item.
        $icon = NULL;
        if ($callableIcon instanceof \Closure) {
          $icon = $callableIcon($item);
        } elseif ($callableIcon !== NULL) {
          $icon = $callableIcon;
        }

        // Get text representation of this item.
        $text = NULL;
        if ($callableText instanceof \Closure) {
          $text = $callableText($item);
        } elseif ($callableText !== NULL) {
          $text = $item->{$callableText}();
        }

        // Get the link to the item (if possible).
        $link = $item->getId();
        if ($callableRoute instanceof \Closure) {
          $link = $callableRoute($item, $this->sh->router());
        } elseif ($callableRoute !== NULL) {
          try {
            $link = '<a href="'.$this->sh->router()->generate($callableRoute, ['id' => $item->getId()]).'" title="'.$text.'">'.$item->getId().'</a>';
          } catch (\Exception $e) {
          }
        }

        $message .= '<li>';
        $message .= $icon.$link.': '.$text;
        $message .= '</li>';
      }

      $message .= '</ul>';
    }

    return $message;
  }

}
