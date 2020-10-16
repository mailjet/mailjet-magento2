<?php

namespace Mailjet\Mailjet\Observer\Checkout;

class QuoteSubmitSuccess implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Mailjet\Mailjet\Helper\Data
     */
    private $dataHelper;

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    private $subscriberFactory;

    /**
     * Quote Submit Success constructor.
     *
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     */
    public function __construct(
        \Mailjet\Mailjet\Helper\Data $dataHelper,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
    ) {
        $this->dataHelper           = $dataHelper;
        $this->subscriberFactory    = $subscriberFactory;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return Void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ECOMMERCE_CHECKOUT_PAGE_SUBSCRIBE)) {
            /**
             * @var $subscriber \Magento\Quote\Model\Quote
             */
            $quote = $observer->getQuote();

            if ($quote->getNewsletterSubscribe()) {
                $email = $quote->getCustomerEmail();
                $subscriber = $this->subscriberFactory->create()->loadByEmail($email);

                if (!$subscriber->getId()) {
                    $subscriber->subscribe($email);
                }
            }
        }
    }
}
