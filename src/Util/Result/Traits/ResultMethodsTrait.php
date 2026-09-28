<?php

namespace HBM\BasicsBundle\Util\Result\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResultMethodsTrait
{
    public function merge(self $result): self
    {
        if (method_exists($this, 'addMessages') && method_exists($result, 'getMessages')) {
            $this->addMessages($result->getMessages());
        }

        if (method_exists($this, 'setPayload') && method_exists($result, 'getPayloads')) {
            foreach ($result->getPayloads() as $payloadKey => $payloadValue) {
                $this->setPayload($payloadKey, $payloadValue);
            }
        }

        if (method_exists($this, 'addNotice') && method_exists($result, 'getNotices')) {
            foreach ($result->getNotices() as $notice) {
                $this->addNotice($notice);
            }
        }

        if (method_exists($this, 'getReturn') && method_exists($this, 'setReturn') && method_exists($result, 'getReturn')) {
            if (($this->getReturn() === false) || ($result->getReturn() === false)) {
                $this->setReturn(false);
            } elseif (($this->getReturn() === null) || ($result->getReturn() === null)) {
                $this->setReturn(null);
            }
        }

        return $this;
    }

    public function jsonResponse(): JsonResponse
    {
        $status = Response::HTTP_OK;

        $data = [];

        if (method_exists($this, 'getReturn')) {
            $status          = $this->getReturn() ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;
            $data['success'] = $this->getReturn();
        }

        if (method_exists($this, 'getMessagesArray')) {
            $data['messages'] = $this->getMessagesArray();
        }

        return new JsonResponse($data, $status);
    }
}
