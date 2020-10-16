<?php

namespace Mailjet\Mailjet\Observer\Customer;

class RegisterSuccess implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var  \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface
     */
    protected $subscriberQueueRepository;

    /**
     * @var \Magento\Newsletter\Model\ResourceModel\Subscriber
     */
    protected $subscriberResourceModel;

    /**
     * Register Customer constructor.
     *
     * @param  \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface $subscriberQueueRepository
     * @param \Magento\Newsletter\Model\ResourceModel\Subscriber $subscriberResourceModel
     */
    public function __construct(
        \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface $subscriberQueueRepository,
        \Magento\Newsletter\Model\ResourceModel\Subscriber $subscriberResourceModel
    ) {
        $this->subscriberQueueRepository = $subscriberQueueRepository;
        $this->subscriberResourceModel   = $subscriberResourceModel;
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
         * @var $customer \Magento\Customer\Model\Customer
         */
        $customer = $observer->getCustomer();

        $subscriber = $this->subscriberResourceModel->loadByCustomerData($customer);

        if ($subscriber &&
            (
                (is_array($subscriber) && $subscriber['subscriber_status'] == \Magento\Newsletter\Model\Subscriber::STATUS_SUBSCRIBED)
                || (is_object($subscriber) && $subscriber->getSubscriberStatus() == \Magento\Newsletter\Model\Subscriber::STATUS_SUBSCRIBED)
            )
        ) {
            $this->subscriberQueueRepository->subscribe($customer);
        } else {
            $this->subscriberQueueRepository->unsubscribe($customer);
        }
    }
}
