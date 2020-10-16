<?php

namespace Mailjet\Mailjet\Plugin\Email\Model;

class Transport
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Mailjet\Mailjet\Model\Framework\Mail\TransportFactory
     */
    private $transportFactory;

    /**
     * @var \Mailjet\Mailjet\Helper\Data
     */
    private $dataHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Mailjet\Mailjet\Model\Framework\Mail\TransportFactory $transportFactory
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Mailjet\Mailjet\Model\Framework\Mail\TransportFactory $transportFactory,
        \Mailjet\Mailjet\Helper\Data $dataHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig      = $scopeConfig;
        $this->transportFactory = $transportFactory;
        $this->dataHelper       = $dataHelper;
        $this->storeManager     = $storeManager;
    }

    /**
     * Around Send Message.
     *
     * @param \Magento\Email\Model\Transport $subject
     * @param callable $proceed
     */
    public function aroundSendMessage(\Magento\Email\Model\Transport $subject, callable $proceed)
    {
        $storeId = $this->storeManager->getStore()->getStoreId();

        if ($this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ACCOUNT_SMTP_ACTIVE, $storeId)
            && $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ACCOUNT_ACTIVE, $storeId)
        ) {
            $smtp = $this->transportFactory->create();
            $config = $this->dataHelper->getSmtpConfigs($storeId);
            $isSetReturnPath = $this->scopeConfig->getValue(\Magento\Email\Model\Transport::XML_PATH_SENDING_SET_RETURN_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $returnPathValue = $this->scopeConfig->getValue(\Magento\Email\Model\Transport::XML_PATH_SENDING_RETURN_PATH_EMAIL, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

            try {
                $zendMessage = \Zend\Mail\Message::fromString($subject->getMessage()->getRawMessage())->setEncoding('utf-8');
                if (2 === $isSetReturnPath && $returnPathValue) {
                    $zendMessage->setSender($returnPathValue);
                } elseif (1 === $isSetReturnPath && $zendMessage->getFrom()->count()) {
                    $fromAddressList = $zendMessage->getFrom();
                    $fromAddressList->rewind();
                    $zendMessage->setSender($fromAddressList->current()->getEmail());
                }

                $smtp->sendSmtpMessage($zendMessage, $config);
            } catch (\Exception $e) {
                throw new \Magento\Framework\Exception\MailException(new \Magento\Framework\Phrase($e->getMessage()), $e);
            }
        } else {
            $proceed();
        }
    }
}
