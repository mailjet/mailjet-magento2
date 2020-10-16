<?php

namespace Mailjet\Mailjet\Helper\Api;

use \Mailjet\Mailjet\Helper\MailjetAPI;

trait Message
{
    abstract public function getResponce();
    abstract public function getError();
    abstract protected function setResponce($responce);
    abstract protected function setError($error);
    abstract protected function getClient();

    public function getMessage($messageId)
    {
        $body = [
            MailjetAPI::ID => $messageId
        ];

        $this->setResponce($this->getClient()->get(\Mailjet\Resources::$Message, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }
}
