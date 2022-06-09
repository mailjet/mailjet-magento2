<?php

namespace Mailjet\Mailjet\Helper\Api;

use Mailjet\Mailjet\Helper\MailjetAPI;

trait Webhook
{
    /**
     * Get Response
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
     * Create webhook
     *
     * @param array $data
     * @return array
     */
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

        $this->setResponce($this->getClient()
            ->post(\Mailjet\Resources::$Eventcallbackurl, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    /**
     * Delete webhook
     *
     * @param string|int $webhookId
     * @return array
     */
    public function deleteWebhook($webhookId)
    {
        $body = [
            MailjetAPI::ID => $webhookId
        ];

        $this->setResponce($this->getClient()
            ->delete(\Mailjet\Resources::$Eventcallbackurl, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    /**
     * Update webhook
     *
     * @param array $data
     * @return array
     */
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

        $this->setResponce($this->getClient()
            ->put(\Mailjet\Resources::$Eventcallbackurl, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    /**
     * Get webhooks
     *
     * @return array
     */
    public function getWebhooks()
    {
        $this->setResponce($this->getClient()
            ->get(\Mailjet\Resources::$Eventcallbackurl, [], ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }
}
