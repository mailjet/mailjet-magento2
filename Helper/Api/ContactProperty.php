<?php

namespace Mailjet\Mailjet\Helper\Api;

use Mailjet\Mailjet\Helper\MailjetAPI;

trait ContactProperty
{
    /**
     * Get response
     *
     * @return mixed
     */
    abstract public function getResponce();

    /**
     * Get error
     *
     * @return mixed
     */
    abstract public function getError();

    /**
     * Set response
     *
     * @param array $responce
     * @return mixed
     */
    abstract protected function setResponce($responce);

    /**
     * Set error
     *
     * @param array $error
     * @return mixed
     */
    abstract protected function setError($error);

    /**
     * Get client
     *
     * @return mixed
     */
    abstract protected function getClient();

    /**
     * Get properties
     *
     * @return array
     */
    public function getProperties()
    {
        $this->setResponce($this->getClient()->get(\Mailjet\Resources::$Contactmetadata, [], ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    /**
     * Create property
     *
     * @param array $data
     * @return array
     */
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

    /**
     * Update contact data
     *
     * @param string $email
     * @param array $properties
     * @return array
     */
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
