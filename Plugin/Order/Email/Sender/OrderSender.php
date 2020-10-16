<?php

namespace Mailjet\Mailjet\Plugin\Order\Email\Sender;

class OrderSender
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
     * @var \Magento\Sales\Model\ResourceModel\Order
     */
    protected $orderResource;

    /**
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     * @param \Mailjet\Mailjet\Model\Api\Email $apiEmail
     * @param \Magento\Sales\Model\ResourceModel\Order $orderResource
     */
    public function __construct(
        \Mailjet\Mailjet\Helper\Data $dataHelper,
        \Mailjet\Mailjet\Model\Api\Email $apiEmail,
        \Magento\Sales\Model\ResourceModel\Order $orderResource
    ) {
        $this->dataHelper    = $dataHelper;
        $this->apiEmail      = $apiEmail;
        $this->orderResource = $orderResource;
    }

    /**
     * Around Send Message.
     *
     * @param \Magento\Sales\Model\Order\Email\Sender\OrderSender $subject
     * @param callable $proceed
     * @param \Magento\Sales\Model\Order\Order $order
     * @param bool $forceSyncMode
     * @return bool
     */
    public function aroundSend(\Magento\Sales\Model\Order\Email\Sender\OrderSender $subject, callable $proceed, \Magento\Sales\Model\Order $order, $forceSyncMode = false)
    {
        $storeId = $order->getStore()->getStoreId();
        if ($this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ORDER_NOTIFICATION_ORDER_CONFIRMATION_STATUS, $storeId)
            && $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ORDER_NOTIFICATION_ORDER_CONFIRMATION_TEMPLATE_ID, $storeId)
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
