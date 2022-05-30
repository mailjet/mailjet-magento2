<?php

namespace Mailjet\Mailjet\Observer\Order;

use Mailjet\Mailjet\Helper\Data;

class CancelAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Mailjet\Mailjet\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Mailjet\Mailjet\Model\Api\Email
     */
    protected $apiEmail;

    /**
     * Order Cancel After constructor.
     *
     * @param \Mailjet\Mailjet\Helper\Data     $dataHelper
     * @param \Mailjet\Mailjet\Model\Api\Email $apiEmail
     */
    public function __construct(
        Data $dataHelper,
        \Mailjet\Mailjet\Model\Api\Email $apiEmail
    ) {
        $this->dataHelper = $dataHelper;
        $this->apiEmail   = $apiEmail;
    }

    /**
     * Execute observer
     *
     * @param  \Magento\Framework\Event\Observer $observer
     * @return Void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /**
         * @var $order \Magento\Sales\Model\Order
         */
        $order = $observer->getOrder();
        $storeId = $order->getStore()->getId();

        if ($this->dataHelper
                ->getConfigValue(Data::CONFIG_PATH_ORDER_NOTIFICATION_ORDER_CANCELLATION_TEMPLATE_ID, $storeId)
            && $this->dataHelper
                ->getConfigValue(Data::CONFIG_PATH_ORDER_NOTIFICATION_ORDER_CANCELLATION_STATUS, $storeId)
        ) {
            $this->apiEmail->cancelOrder($order, $storeId);
        }
    }
}
