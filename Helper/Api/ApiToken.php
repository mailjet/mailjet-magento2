<?php

namespace Mailjet\Mailjet\Helper\Api;

use \Mailjet\Mailjet\Helper\MailjetAPI;

trait ApiToken
{
    abstract public function getApiKey();
    abstract public function getResponce();
    abstract public function getError();
    abstract protected function setResponce($responce);
    abstract protected function setError($error);
    abstract protected function getClient();

    public function getApiToken($pages, $sessionExpiration)
    {
        $body = [
            MailjetAPI::BODY => [
                MailjetAPI::ALLOWED_ACCESS => implode(',', $pages),
                MailjetAPI::IS_ACTIVE      => 'true',
                MailjetAPI::TOKEN_TYPE     => 'iframe',
                MailjetAPI::API_KEY_ALT    => $this->getAPIKey(),
                MailjetAPI::VALID_FOR      => $sessionExpiration
            ]
        ];

        $this->setResponce($this->getClient()->post(\Mailjet\Resources::$Apitoken, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }
}
