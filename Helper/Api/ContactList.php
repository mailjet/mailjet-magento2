<?php

namespace Mailjet\Mailjet\Helper\Api;

use \Mailjet\Mailjet\Helper\MailjetAPI;

trait ContactList
{
    abstract public function getResponce();
    abstract public function getError();
    abstract protected function setResponce($responce);
    abstract protected function setError($error);
    abstract protected function getClient();

    public function createContactList($name)
    {
        $body = [
            MailjetAPI::BODY => [
                MailjetAPI::NAME => $name
            ]
        ];

        $this->setResponce($this->getClient()->post(\Mailjet\Resources::$Contactslist, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    public function getAllContactLists()
    {
        $this->setResponce($this->getClient()->get(\Mailjet\Resources::$Contactslist, [], ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }
}
