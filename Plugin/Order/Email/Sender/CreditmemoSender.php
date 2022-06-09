<?php

namespace Mailjet\Mailjet\Plugin\Order\Email\Sender;

use Magento\Sales\Model\Order\Creditmemo;
use Mailjet\Mailjet\Helper\Data;
use Mailjet\Mailjet\Model\Api\Email;

class CreditmemoSender
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
     * @var \Magento\Sales\Model\ResourceModel\Order\Creditmemo
     */
    protected $creditmemoResource;

    /**
     * @param Data $dataHelper
     * @param Email $apiEmail
     * @param \Magento\Sales\Model\ResourceModel\Order\Creditmemo $creditmemoResource
     */
    public function __construct(
        Data                                                $dataHelper,
        Email                                               $apiEmail,
        \Magento\Sales\Model\ResourceModel\Order\Creditmemo $creditmemoResource
    ) {
        $this->dataHelper = $dataHelper;
        $this->apiEmail = $apiEmail;
        $this->creditmemoResource = $creditmemoResource;
    }

    /**
     * Around Send Message.
     *
     * @param \Magento\Sales\Model\Order\Email\Sender\CreditmemoSender $subject
     * @param callable $proceed
     * @param Creditmemo $creditmemo
     * @param bool $forceSyncMode
     * @return bool
     */
    public function aroundSend(
        \Magento\Sales\Model\Order\Email\Sender\CreditmemoSender $subject,
        callable $proceed,
        Creditmemo                                               $creditmemo,
        $forceSyncMode = false
    ) {
        $storeId = $creditmemo->getOrder()->getStore()->getStoreId();
        if ($this->dataHelper->getConfigValue(
            Data::CONFIG_PATH_ORDER_NOTIFICATION_REFUND_CONFIRMATION_STATUS,
            $storeId
        )
            && $this->dataHelper->getConfigValue(
                Data::CONFIG_PATH_ORDER_NOTIFICATION_ORDER_CONFIRMATION_TEMPLATE_ID,
                $storeId
            )
        ) {
            $result = $this->apiEmail->newCreditMemo($creditmemo, $storeId);

            if ($result) {
                $creditmemo->setEmailSent(true);
                $this->creditmemoResource->saveAttribute($creditmemo, ['send_email', 'email_sent']);
                return true;
            } else {
                return $proceed($creditmemo, $forceSyncMode);
            }
        } else {
            return $proceed($creditmemo, $forceSyncMode);
        }
    }
}
