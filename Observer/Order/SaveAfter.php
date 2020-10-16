<?php

namespace Mailjet\Mailjet\Observer\Order;

class SaveAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var  \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface
     */
    protected $subscriberQueueRepository;

    /**
     * @var  \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * Order Save After constructor.
     *
     * @param  \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface $subscriberQueueRepository
     * @param  \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface $subscriberQueueRepository,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
    ) {
        $this->subscriberQueueRepository = $subscriberQueueRepository;
        $this->customerRepository        = $customerRepository;
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
         * @var $order \Magento\Sales\Model\Order
         */
        $order = $observer->getOrder();

        if ($order->getCustomerId()) {
            $customer = $this->customerRepository->getById($order->getCustomerId());

            if ($customer) {
                $this->subscriberQueueRepository->updateEcommerceData($customer);
            }
        }
    }
}
