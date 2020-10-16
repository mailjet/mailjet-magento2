<?php

namespace Mailjet\Mailjet\Api;

interface SubscriberEventInterface
{
    /**
     * Mailjet unsubscribe event
     *
     * @return void
     */
    public function unsub();
}
