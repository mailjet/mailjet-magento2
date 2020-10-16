<?php

namespace Mailjet\Mailjet\Model\Api;

class Ecommerce
{
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * Ecommerce construncor
     *
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     */
    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
    ) {
        $this->customerRepository     = $customerRepository;
        $this->orderCollectionFactory = $orderCollectionFactory;
    }

    /**
     * Get Ecommerce Data
     *
     * @param \Magento\Customer\Model\Customer
     * @return Array
     */
    public function getEcommerceData($customer)
    {
        if (is_integer($customer)) {
            $customer = $this->customerRepository->getById($customer);
        }

        $customerId = $customer->getId();

        $orders = $this->orderCollectionFactory->create()
            ->addFieldToSearchFilter(\Magento\Sales\Api\Data\OrderInterface::CUSTOMER_ID, ['eq' => $customerId])
            ->setOrder('entity_id', 'ASC');

        $orderCount = $orders->getTotalCount();
        $total = 0;
        $lastOrderDate = '';
        $customerCreatedDate = $customer->getCreatedAt();

        foreach ($orders as $order) {
            $total += $order->getBaseGrandTotal() - $order->getBaseTotalRefunded();
            $lastOrderDate = $order->getCreatedAt();
        }

        return [
            \Mailjet\Mailjet\Helper\Data::REST_API_CONTACT_PROPERTIES['total_orders_count']['name'] => $orderCount,
            \Mailjet\Mailjet\Helper\Data::REST_API_CONTACT_PROPERTIES['total_spent']['name'] => $total,
            \Mailjet\Mailjet\Helper\Data::REST_API_CONTACT_PROPERTIES['last_order_date']['name'] => $lastOrderDate,
            \Mailjet\Mailjet\Helper\Data::REST_API_CONTACT_PROPERTIES['account_creation_date']['name'] => $customerCreatedDate,
        ];
    }
}
