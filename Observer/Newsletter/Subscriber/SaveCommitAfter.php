<?php

namespace Mailjet\Mailjet\Observer\Newsletter\Subscriber;

class SaveCommitAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface
     */
    protected $subscriberQueueRepository;

    /**
     * Save Subscriber constructor.
     *
     * @param \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface $subscriberQueueRepository
     */
    public function __construct(
        \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface $subscriberQueueRepository
    ) {
        $this->subscriberQueueRepository           = $subscriberQueueRepository;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return Void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /**
         * @var $subscriber \Magento\Newsletter\Model\Subscriber
         */
        $subscriber = $observer->getSubscriber();

        if ($subscriber->isStatusChanged()) {
            if ($subscriber->getSubscriberStatus() == \Magento\Newsletter\Model\Subscriber::STATUS_SUBSCRIBED) {
                $this->subscriberQueueRepository->subscribe($subscriber);
            } else {
                $this->subscriberQueueRepository->unsubscribe($subscriber);
            }
        }
    }
}
