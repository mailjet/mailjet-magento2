<?php

namespace Mailjet\Mailjet\Helper\Api;

use \Mailjet\Mailjet\Helper\MailjetAPI;

trait Webhook
{
    abstract public function getResponce();
    abstract public function getError();
    abstract protected function setResponce($responce);
    abstract protected function setError($error);
    abstract protected function getClient();

    public function createWebhook($data)
    {
        $body = [
            MailjetAPI::BODY => [
                MailjetAPI::EVENT_TYPE => $data['event_type'],
                // MailjetAPI::IS_BACKUP  => $data['is_backup'],
                MailjetAPI::STATUS     => $data['status'],
                MailjetAPI::URL        => $data['url'],
            ]
        ];

        $this->setResponce($this->getClient()->post(\Mailjet\Resources::$Eventcallbackurl, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    public function deleteWebhook($webhookId)
    {
        $body = [
            MailjetAPI::ID => $webhookId
        ];

        $this->setResponce($this->getClient()->delete(\Mailjet\Resources::$Eventcallbackurl, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    public function updateWebhook($data)
    {
        $body = [
            MailjetAPI::BODY => [
                MailjetAPI::EVENT_TYPE => $data['event_type'],
                // MailjetAPI::IS_BACKUP  => $data['is_backup'],
                MailjetAPI::STATUS     => $data['status'],
                MailjetAPI::URL        => $data['url'],
            ]
        ];

        $this->setResponce($this->getClient()->put(\Mailjet\Resources::$Eventcallbackurl, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    public function getWebhooks()
    {
        $this->setResponce($this->getClient()->get(\Mailjet\Resources::$Eventcallbackurl, [], ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }
}
