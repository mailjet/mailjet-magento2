<?php

namespace Mailjet\Mailjet\Plugin\Framework\Mail;

class Transport
{
    /**
     * @var \Mailjet\Mailjet\Model\Framework\Mail\TransportFactory
     */
    protected $transportFactory;

    /**
     * @var \Mailjet\Mailjet\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \Mailjet\Mailjet\Model\Framework\Mail\TransportFactory $transportFactory
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Mailjet\Mailjet\Model\Framework\Mail\TransportFactory $transportFactory,
        \Mailjet\Mailjet\Helper\Data $dataHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->transportFactory = $transportFactory;
        $this->dataHelper       = $dataHelper;
        $this->storeManager     = $storeManager;
    }

    /**
     * Around Send Message.
     *
     * @param \Magento\Framework\Mail\TransportInterface $subject
     * @param callable $proceed
     */
    public function aroundSendMessage(\Magento\Framework\Mail\TransportInterface $subject, callable $proceed)
    {
        $storeId = $this->storeManager->getStore()->getStoreId();
        if ($this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ACCOUNT_SMTP_ACTIVE, $storeId)
            && $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ACCOUNT_ACTIVE, $storeId)
        ) {
            $message = $subject->getMessage();
            $smtp = $this->transportFactory->create();
            $config = $this->dataHelper->getSmtpConfigs($storeId);
            $smtp->sendSmtpMessage($message, $config);
        } else {
            $proceed();
        }
    }
}
