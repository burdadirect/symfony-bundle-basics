<?php

namespace HBM\BasicsBundle\Service;

use HBM\BasicsBundle\Entity\AbstractEntity;
use HBM\BasicsBundle\Util\ConfirmMessage\ConfirmMessage;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractDeletionHelper {

  private ?RouterInterface $router = null;

  public function __construct(RouterInterface $router) {
    $this->router = $router;
  }

  /**
   * @param array $confirmMessages
   *
   * @return string
   */
  public function renderConfirmMessages(array $confirmMessages) : string {
    $confirmDetails = '';
    foreach ($confirmMessages as $confirmMessage) {
      if ($confirmMessage instanceof ConfirmMessage) {
        $confirmDetails .= $this->renderConfirmMessage($confirmMessage);
      } else {
        $confirmDetails .= $confirmMessage;
      }
    }
    return $confirmDetails;
  }

  /**
   * @param ConfirmMessage $confirmMessage
   * @param string|null $headline
   *
   * @return string
   */
  public function renderConfirmMessage(ConfirmMessage $confirmMessage, string $headline = NULL) : string {
    $message = '';
    if (\count($confirmMessage->getItems()) > 0) {
      $listItems = '';
      foreach ($confirmMessage->getItems() as $item) {
        if ($confirmMessage->evalDiscard($item)) {
          continue;
        }
        $listItems .= $this->renderListItem($confirmMessage, $item);
      }

      $message .= $headline ?: $this->renderHeadline($confirmMessage);
      $message .= $this->renderList($listItems);
    } elseif ($confirmMessage->getMessage()) {
      $message .= $confirmMessage->getMessage();
    }

    return $message;
  }

  /**
   * @param ConfirmMessage $confirmMessage
   *
   * @return string
   */
  public function renderHeadline(ConfirmMessage $confirmMessage) : string {
    return '<p>An das Objekt sind folgende <strong>'.$confirmMessage->getWording().'</strong> geknüpft:</p>';
  }

  /**
   * @param $listItems
   *
   * @return string
   */
  public function renderList($listItems) : string {
    $list = '<ul class="tree">';
    $list .= $listItems;
    $list .= '</ul>';

    return $list;
  }

  /**
   * @param ConfirmMessage $confirmMessage
   * @param AbstractEntity $item
   *
   * @return string
   */
  public function renderListItem(ConfirmMessage $confirmMessage, AbstractEntity $item) : string {
    $listItem = '<li>';
    $listItem .= $confirmMessage->render($item, $this->router);
    $listItem .= $this->renderConfirmMessages($confirmMessage->evalChildren($item));
    $listItem .= '</li>';

    return $listItem;
  }

}
