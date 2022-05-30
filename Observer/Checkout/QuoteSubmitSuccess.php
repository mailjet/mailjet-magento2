<?php

namespace Mailjet\Mailjet\Observer\Checkout;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Newsletter\Model\SubscriberFactory;
use Mailjet\Mailjet\Helper\Data;

class QuoteSubmitSuccess implements ObserverInterface
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
     * @param \Mailjet\Mailjet\Helper\Data                $dataHelper
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     */
    public function __construct(
        Data $dataHelper,
        SubscriberFactory $subscriberFactory
    ) {
        $this->dataHelper           = $dataHelper;
        $this->subscriberFactory    = $subscriberFactory;
    }

    /**
     * Execute observer
     *
     * @param  \Magento\Framework\Event\Observer $observer
     * @return Void
     */
    public function execute(Observer $observer)
    {
        if ($this->dataHelper->getConfigValue(Data::CONFIG_PATH_ECOMMERCE_CHECKOUT_PAGE_SUBSCRIBE)) {
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
