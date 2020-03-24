<?php

namespace HBM\BasicsBundle\Service;

use HBM\BasicsBundle\Entity\AbstractEntity;
use HBM\BasicsBundle\Util\ConfirmMessage\ConfirmMessage;
use HBM\BasicsBundle\Util\ConfirmMessage\ConfirmMessageInterface;

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
   * @param array $confirmMessages
   *
   * @return string
   */
  public function renderConfirmMessages(array $confirmMessages) : string {
    $confirmDetails = '';
    foreach ($confirmMessages as $confirmMessage) {
      $confirmDetails .= $this->renderConfirmMessage($confirmMessage);
    }
    return $confirmDetails;
  }

  /**
   * @param ConfirmMessage $confirmMessage
   *
   * @return string
   */
  public function renderConfirmMessage(ConfirmMessage $confirmMessage) : string {
    $message = '';
    if (\count($confirmMessage->getItems()) > 0) {
      $message .= '<p class="mb-1">An das Objekt sind folgende <strong>'.$confirmMessage->getWording().'</strong> geknüpft:</p>';
      $message .= '<ul class="tree">';

      foreach ($confirmMessage->getItems() as $item) {
        if ($confirmMessage->evalDiscard($item)) {
          continue;
        }

        $id = $confirmMessage->evalId($item);
        $url = $confirmMessage->evalUrl($item, $this->sh->router());
        $text = $confirmMessage->evalText($item);
        $icon = $confirmMessage->evalIcon($item);

        $message .= '<li>';
        $message .= $confirmMessage->evalFormat($id, $url, $text, $icon);
        $message .= $this->renderConfirmMessages($confirmMessage->evalChildren($item));
        $message .= '</li>';
      }

      $message .= '</ul>';
    }

    return $message;
  }

}
