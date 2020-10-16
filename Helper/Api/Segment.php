<?php

namespace Mailjet\Mailjet\Helper\Api;

use \Mailjet\Mailjet\Helper\MailjetAPI;

trait Segment
{
    abstract public function getResponce();
    abstract public function getError();
    abstract protected function setResponce($responce);
    abstract protected function setError($error);
    abstract protected function getClient();

    public function getSegments()
    {
        $this->setResponce($this->getClient()->get(\Mailjet\Resources::$Contactfilter, [], ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

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
