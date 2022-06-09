<?php

namespace Mailjet\Mailjet\Helper\Api;

use Mailjet\Mailjet\Helper\MailjetAPI;

trait ApiToken
{
    /**
     * Get api key
     *
     * @return mixed
     */
    abstract public function getApiKey();

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
     * Set responce
     *
     * @param array $responce
     * @return mixed
     */
    abstract protected function setResponce($responce);

    /**
     * SetError
     *
     * @param array $error
     * @return mixed
     */
    abstract protected function setError($error);

    /**
     * GetClient
     *
     * @return mixed
     */
    abstract protected function getClient();

    /**
     * Get api token
     *
     * @param array $pages
     * @param string $sessionExpiration
     * @return array
     */
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
