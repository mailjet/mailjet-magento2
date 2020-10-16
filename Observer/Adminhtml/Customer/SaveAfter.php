<?php

namespace Mailjet\Mailjet\Observer\Adminhtml\Customer;

class SaveAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface
     */
    protected $subscriberQueueRepository;

    /**
     * @var \Magento\Newsletter\Model\ResourceModel\Subscriber
     */
    protected $subscriberResourceModel;

    /**
     * Customer Save After constructor.
     *
     * @param \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface $subscriberQueueRepository
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
        $originalRequestData = $observer->getRequest()->getPostValue(\Magento\Customer\Api\CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER);

        if (!isset($originalRequestData['entity_id'])) {
            $subscriber = $this->subscriberResourceModel->loadByCustomerData($customer);

            if ($subscriber && $subscriber->getSubscriberStatus() == \Magento\Newsletter\Model\Subscriber::STATUS_SUBSCRIBED) {
                $this->subscriberQueueRepository->subscribe($subscriber);
            } else {
                $this->subscriberQueueRepository->unsubscribe($customer);
            }
        }
    }
}
