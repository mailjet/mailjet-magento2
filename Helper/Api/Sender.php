<?php

namespace Mailjet\Mailjet\Helper\Api;

use Mailjet\Mailjet\Helper\MailjetAPI;

trait Sender
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
     * Get senders
     *
     * @return array
     */
    public function getSenders()
    {
        $this->setResponce($this->getClient()->get(\Mailjet\Resources::$Sender, [], ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }
}
