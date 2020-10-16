<?php

namespace Mailjet\Mailjet\Helper\Api;

use \Mailjet\Mailjet\Helper\MailjetAPI;

trait Contact
{
    abstract public function getResponce();
    abstract public function getError();
    abstract protected function setResponce($responce);
    abstract protected function setError($error);
    abstract protected function getClient();

    public function getAllContacts()
    {
        $this->setResponce($this->getClient()->get(\Mailjet\Resources::$Contact, [], ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    /**
     * @param Int $contactListId
     * @param Array $contacts array(name, email)
     * @param String $action MailjetAPI::ACTIONS
     */
    public function manageContacts($contactListId, $contacts, $action)
    {
        if (in_array($action, MailjetAPI::CONTACT_ACTIONS) && !empty($contacts)) {
            $subscribers = [];

            foreach ($contacts as $contact) {
                $subscriber = [
                    MailjetAPI::EMAIL       => $contact['email'],
                    MailjetAPI::NAME        => $contact['name'],
                    MailjetAPI::PROPERTIES  => json_decode($contact['property']) // {"key":value}
                ];

                $subscribers[] = $subscriber;
            }

            $body = [
                MailjetAPI::ID => (int)$contactListId,
                MailjetAPI::BODY => [
                    MailjetAPI::ACTION   => $action,
                    MailjetAPI::CONTACTS => $subscribers
                ]
            ];

            $this->setResponce($this->getClient()->post(\Mailjet\Resources::$ContactslistManagemanycontacts, $body, ['version' => 'v3']));

            if ($this->getResponce()->success()) {
                return $this->getResponce()->getData();
            } else {
                return [];
            }
        } else {
            return [];
        }
    }
}
