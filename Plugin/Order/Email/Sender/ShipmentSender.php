<?php

namespace Mailjet\Mailjet\Plugin\Order\Email\Sender;

use Exception;
use Magento\Sales\Model\Order\Shipment;
use Mailjet\Mailjet\Helper\Data;
use Mailjet\Mailjet\Model\Api\Email;

class ShipmentSender
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
     * @var \Magento\Sales\Model\ResourceModel\Order\Shipment
     */
    protected $shipmentResource;

    /**
     * @param Data $dataHelper
     * @param Email $apiEmail
     * @param \Magento\Sales\Model\ResourceModel\Order\Shipment $shipmentResource
     */
    public function __construct(
        Data                                              $dataHelper,
        Email                                             $apiEmail,
        \Magento\Sales\Model\ResourceModel\Order\Shipment $shipmentResource
    ) {
        $this->dataHelper = $dataHelper;
        $this->apiEmail = $apiEmail;
        $this->shipmentResource = $shipmentResource;
    }

    /**
     * Around Send Message.
     *
     * @param \Magento\Sales\Model\Order\Email\Sender\ShipmentSender $subject
     * @param callable $proceed
     * @param Shipment $shipment
     * @param bool $forceSyncMode
     * @return bool
     * @throws Exception
     */
    public function aroundSend(
        \Magento\Sales\Model\Order\Email\Sender\ShipmentSender $subject,
        callable                                               $proceed,
        Shipment                                               $shipment,
        $forceSyncMode = false
    ) {
        $storeId = $shipment->getOrder()->getStore()->getStoreId();
        if ($this->dataHelper->getConfigValue(
            Data::CONFIG_PATH_ORDER_NOTIFICATION_SHIPPING_CONFIRMATION_STATUS,
            $storeId
        )
            && $this->dataHelper->getConfigValue(
                Data::CONFIG_PATH_ORDER_NOTIFICATION_SHIPPING_CONFIRMATION_TEMPLATE_ID,
                $storeId
            )
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
