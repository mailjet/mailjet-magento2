<?php

namespace Mailjet\Mailjet\Plugin\Order\Email\Sender;

use Exception;
use Magento\Sales\Model\Order;
use Mailjet\Mailjet\Helper\Data;
use Mailjet\Mailjet\Model\Api\Email;

class OrderSender
{
    /**
     * @var Data
     */
    protected $dataHelper;

    /**
     * @var Email
     */
    protected $apiEmail;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order
     */
    protected $orderResource;

    /**
     * @param Data $dataHelper
     * @param Email $apiEmail
     * @param \Magento\Sales\Model\ResourceModel\Order $orderResource
     */
    public function __construct(
        Data                                     $dataHelper,
        Email                                    $apiEmail,
        \Magento\Sales\Model\ResourceModel\Order $orderResource
    ) {
        $this->dataHelper = $dataHelper;
        $this->apiEmail = $apiEmail;
        $this->orderResource = $orderResource;
    }

    /**
     * Around Send Message.
     *
     * @param Order\Email\Sender\OrderSender $subject
     * @param callable $proceed
     * @param Order $order
     * @param bool $forceSyncMode
     * @return bool
     * @throws Exception
     */
    public function aroundSend(
        Order\Email\Sender\OrderSender $subject,
        callable                       $proceed,
        Order                          $order,
        $forceSyncMode = false
    ) {
        $storeId = $order->getStore()->getStoreId();
        if ($this->dataHelper->getConfigValue(
            Data::CONFIG_PATH_ORDER_NOTIFICATION_ORDER_CONFIRMATION_STATUS,
            $storeId
        )
            && $this->dataHelper->getConfigValue(
                Data::CONFIG_PATH_ORDER_NOTIFICATION_ORDER_CONFIRMATION_TEMPLATE_ID,
                $storeId
            )
        ) {
            $result = $this->apiEmail->newOrder($order, $storeId);

            if ($result) {
                $order->setEmailSent(true);
                $this->orderResource->saveAttribute($order, 'email_sent');
                return true;
            } else {
                return $proceed($order, $forceSyncMode);
            }
        } else {
            return $proceed($order, $forceSyncMode);
        }
    }
}
