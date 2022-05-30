<?php

namespace Mailjet\Mailjet\Helper\Api;

use Mailjet\Mailjet\Helper\MailjetAPI;

trait ContactList
{
    /**
     * Get responce
     *
     * @return mixed
     */
    abstract public function getResponce();

    /**
     * Get Error
     *
     * @return mixed
     */
    abstract public function getError();

    /**
     * Set Responce
     *
     * @param array $responce
     * @return mixed
     */
    abstract protected function setResponce($responce);

    /**
     * Set Error
     *
     * @param array $error
     * @return mixed
     */
    abstract protected function setError($error);

    /**
     * Get Client
     *
     * @return mixed
     */
    abstract protected function getClient();

    /**
     * Create Contact List
     *
     * @param string $name
     * @return array
     */
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

    /**
     * Get All ContactLists
     *
     * @return array
     */
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
