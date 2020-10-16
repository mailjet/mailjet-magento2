<?php

namespace Mailjet\Mailjet\Helper\Api;

use \Mailjet\Mailjet\Helper\MailjetAPI;

trait ContactProperty
{
    abstract public function getResponce();
    abstract public function getError();
    abstract protected function setResponce($responce);
    abstract protected function setError($error);
    abstract protected function getClient();

    public function getProperties()
    {
        $this->setResponce($this->getClient()->get(\Mailjet\Resources::$Contactmetadata, [], ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    public function createProperty($data)
    {
        $body = [
            MailjetAPI::BODY => [
                MailjetAPI::DATA_TYPE  => $data['data_type'],
                MailjetAPI::NAME       => $data['name'],
                MailjetAPI::NAME_SPACE => $data['name_space']
            ]
        ];

        $this->setResponce($this->getClient()->post(\Mailjet\Resources::$Contactmetadata, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    public function updateContactData($email, $properties)
    {
        $data = [];

        foreach ($properties as $name => $value) {
            $data[] = [
                MailjetAPI::NAME => $name,
                MailjetAPI::VALUE => $value
            ];
        }

        $body = [
            MailjetAPI::ID => $email,
            MailjetAPI::BODY => [
                MailjetAPI::DATA  => $data
            ]
        ];

        $this->setResponce($this->getClient()->put(\Mailjet\Resources::$Contactdata, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }
}
