<?php

namespace HBM\BasicsBundle\Service;

use HBM\BasicsBundle\Entity\Entity;

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
   * AbstractDeletionHelper constructor.
   *
   * @param AbstractServiceHelper $serviceHelper
   * @param AbstractDoctrineHelper $doctrineHelper
   */
  public function __construct(AbstractServiceHelper $serviceHelper, AbstractDoctrineHelper $doctrineHelper) {
    $this->sh = $serviceHelper;
    $this->dh = $doctrineHelper;
  }

  /**
   * @param $items
   * @param $headline
   * @param $route
   * @param $funcText
   * @param null $funcRemove
   *
   * @return string
   */
  protected function assembleConfirmMessagesForRelatedItems($items, $headline, $route, $funcText, $funcRemove = NULL) : string {
    return $this->assembleConfirmMessages($items, 'An das Objekt sind folgende <strong>'.$headline.'</strong> geknüpft (werden gelöscht):', $route, $funcText, $funcRemove);
  }

  /**
   * @param Entity[] $items
   * @param string $headline
   * @param string $route
   * @param string|callable $funcText
   * @param string $funcRemove
   *
   * @return string
   */
  protected function assembleConfirmMessages($items, $headline, $route, $funcText, $funcRemove = NULL) : string {
    $message = '';
    if (\count($items) > 0) {
      $message .= '<p class="mb-1">'.$headline.'</p>';
      $message .= '<ul class="tree">';

      foreach ($items as $item) {
        $removable = TRUE;
        if ($funcRemove instanceof \Closure) {
          $removable = $funcRemove($item);
        }
        if (!$removable) {
          continue;
        }

        $text = NULL;
        if ($funcText instanceof \Closure) {
          $text = $funcText($item);
        } elseif ($funcText !== NULL) {
          $text = $item->{$funcText}();
        }
        $link = $item->getId();
        if ($route !== NULL) {
          try {
            $link = '<a href="'.$this->sh->router()->generate($route, ['id' => $item->getId()]).'" title="'.$text.'">'.$item->getId().'</a>';
          } catch (\Exception $e) {
          }
        }
        $message .= '<li>';
        $message .= $link.': '.$text;
        $message .= '</li>';
      }

      $message .= '</ul>';
    }

    return $message;
  }

}
