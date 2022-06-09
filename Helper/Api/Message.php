<?php

namespace Mailjet\Mailjet\Helper\Api;

use Mailjet\Mailjet\Helper\MailjetAPI;

trait Message
{
    /**
     * Get Responce
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
     * Get Message
     *
     * @param int|string $messageId
     * @return array
     */
    public function getMessage($messageId)
    {
        $body = [
            MailjetAPI::ID => $messageId
        ];

        $this->setResponce($this->getClient()->get(\Mailjet\Resources::$Message, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }
}
