<?php

namespace Mailjet\Mailjet\Plugin\Order\Email\Sender;

class CreditmemoSender
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
     * @var \Magento\Sales\Model\ResourceModel\Order\Creditmemo
     */
    protected $creditmemoResource;

    /**
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     * @param \Mailjet\Mailjet\Model\Api\Email $apiEmail
     * @param \Magento\Sales\Model\ResourceModel\Order\Creditmemo $creditmemoResource
     */
    public function __construct(
        \Mailjet\Mailjet\Helper\Data $dataHelper,
        \Mailjet\Mailjet\Model\Api\Email $apiEmail,
        \Magento\Sales\Model\ResourceModel\Order\Creditmemo $creditmemoResource
    ) {
        $this->dataHelper         = $dataHelper;
        $this->apiEmail           = $apiEmail;
        $this->creditmemoResource = $creditmemoResource;
    }

    /**
     * Around Send Message.
     *
     * @param \Magento\Sales\Model\Order\Email\Sender\CreditmemoSender $subject
     * @param callable $proceed
     * @param \Magento\Sales\Model\Order\Creditmemo $creditmemo
     * @param bool $forceSyncMode
     * @return bool
     */
    public function aroundSend(\Magento\Sales\Model\Order\Email\Sender\CreditmemoSender $subject, callable $proceed, \Magento\Sales\Model\Order\Creditmemo $creditmemo, $forceSyncMode = false)
    {
        $storeId = $creditmemo->getOrder()->getStore()->getStoreId();
        if ($this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ORDER_NOTIFICATION_REFUND_CONFIRMATION_STATUS, $storeId)
            && $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ORDER_NOTIFICATION_ORDER_CONFIRMATION_TEMPLATE_ID, $storeId)
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
