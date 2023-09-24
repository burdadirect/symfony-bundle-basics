<?php

namespace HBM\BasicsBundle\Service;

use HBM\BasicsBundle\Entity\AbstractEntity;
use HBM\BasicsBundle\Traits\ServiceDependencies\RouterDependencyTrait;
use HBM\BasicsBundle\Util\ConfirmMessage\ConfirmMessage;

abstract class AbstractDeletionHelper
{
    use RouterDependencyTrait;

    public function renderConfirmMessages(array $confirmMessages): string
    {
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

    public function renderConfirmMessage(ConfirmMessage $confirmMessage, string $headline = null): string
    {
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

    public function renderHeadline(ConfirmMessage $confirmMessage): string
    {
        return sprintf($confirmMessage->getHeadline(), $confirmMessage->getWording());
    }

    public function renderList($listItems): string
    {
        $list = '<ul class="tree">';
        $list .= $listItems;
        $list .= '</ul>';

        return $list;
    }

    public function renderListItem(ConfirmMessage $confirmMessage, AbstractEntity $item): string
    {
        $listItem = '<li>';
        $listItem .= $confirmMessage->render($item, $this->router);
        $listItem .= $this->renderConfirmMessages($confirmMessage->evalChildren($item));
        $listItem .= '</li>';

        return $listItem;
    }
}
