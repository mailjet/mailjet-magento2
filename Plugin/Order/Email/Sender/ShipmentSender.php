<?php

namespace Mailjet\Mailjet\Plugin\Order\Email\Sender;

class ShipmentSender
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
     * @var \Magento\Sales\Model\ResourceModel\Order\Shipment
     */
    protected $shipmentResource;

    /**
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     * @param \Mailjet\Mailjet\Model\Api\Email $apiEmail
     * @param \Magento\Sales\Model\ResourceModel\Order\Shipment $shipmentResource
     */
    public function __construct(
        \Mailjet\Mailjet\Helper\Data $dataHelper,
        \Mailjet\Mailjet\Model\Api\Email $apiEmail,
        \Magento\Sales\Model\ResourceModel\Order\Shipment $shipmentResource
    ) {
        $this->dataHelper       = $dataHelper;
        $this->apiEmail         = $apiEmail;
        $this->shipmentResource = $shipmentResource;
    }

    /**
     * Around Send Message.
     *
     * @param \Magento\Sales\Model\Order\Email\Sender\ShipmentSender $subject
     * @param callable $proceed
     * @param \Magento\Sales\Model\Order\Shipment $shipment
     * @param bool $forceSyncMode
     * @return bool
     */
    public function aroundSend(\Magento\Sales\Model\Order\Email\Sender\ShipmentSender $subject, callable $proceed, \Magento\Sales\Model\Order\Shipment $shipment, $forceSyncMode = false)
    {
        $storeId = $shipment->getOrder()->getStore()->getStoreId();
        if ($this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ORDER_NOTIFICATION_SHIPPING_CONFIRMATION_STATUS, $storeId)
            && $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ORDER_NOTIFICATION_SHIPPING_CONFIRMATION_TEMPLATE_ID, $storeId)
        ) {
            $result = $this->apiEmail->newShipment($shipment, $storeId);

            if ($result) {
                $shipment->setEmailSent(true);
                $this->shipmentResource->saveAttribute($shipment, ['send_email', 'email_sent']);
                return true;
            } else {
                return $proceed($shipment, $forceSyncMode);
            }
        } else {
            return $proceed($shipment, $forceSyncMode);
        }
    }
}
