<?php

namespace Mailjet\Mailjet\Model\Api;

use Magento\Newsletter\Model\Subscriber;
use Mailjet\Mailjet\Helper\MailjetAPI;

class SubscriberEvent
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Mailjet\Mailjet\Model\Api\Connection
     */
    protected $apiConnection;

    /**
     * @var \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory
     */
    protected $subscriberCollectionFactory;

    /**
     * Subscriber Event
     *
     * @param \Magento\Framework\App\RequestInterface                              $request
     * @param \Mailjet\Mailjet\Model\Api\Connection                                $apiConnection
     * @param \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory $subscriberCollectionFactory
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Mailjet\Mailjet\Model\Api\Connection $apiConnection,
        \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory $subscriberCollectionFactory
    ) {
        $this->request                     = $request;
        $this->apiConnection               = $apiConnection;
        $this->subscriberCollectionFactory = $subscriberCollectionFactory;
    }

    /**
     * Mailjet unsubscribe event
     */
    public function unsub()
    {
        $post = json_decode($this->request->getContent(), true);

        if (!empty($post[MailjetAPI::MESSAGE_ID]) && !empty($post['mj_contact_id']) && !empty($post['email'])) {
            $message = $this->apiConnection->getConnection()->getMessage($post[MailjetAPI::MESSAGE_ID]);

            if (!empty($message[0]) && $message[0][MailjetAPI::CONTACT_ID] == $post['mj_contact_id']) {
                $subscribers = $this->subscriberCollectionFactory->create()
                    ->addFieldToFilter('subscriber_email', $post['email']);

                foreach ($subscribers as $subscriber) {
                    if ($subscriber->getId() && $subscriber->getSubscriberStatus() == Subscriber::STATUS_SUBSCRIBED) {
                        $subscriber->unsubscribe();
                    }
                }
            }
        }
    }
}
