<?php

namespace Mailjet\Mailjet\Helper\Api;

use Mailjet\Mailjet\Helper\MailjetAPI;

trait Segment
{
    /**
     * Get responce
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
     *  Get segments
     *
     * @return array
     */
    public function getSegments()
    {
        $this->setResponce($this->getClient()->get(\Mailjet\Resources::$Contactfilter, [], ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    /**
     * Create segment
     *
     * @param array $data
     * @return array
     */
    public function createSegment($data)
    {
        $body = [
            MailjetAPI::BODY => [
                MailjetAPI::DESCRIPTION => $data['description'],
                MailjetAPI::EXPRESSION  => $data['expression'],
                MailjetAPI::NAME        => $data['name'],
            ]
        ];

        $this->setResponce($this->getClient()->post(\Mailjet\Resources::$Contactfilter, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }
}
