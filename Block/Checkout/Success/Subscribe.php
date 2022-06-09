<?php

namespace Mailjet\Mailjet\Block\Checkout\Success;

use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template\Context;
use Magento\Newsletter\Model\SubscriberFactory;
use Mailjet\Mailjet\Helper\Data;
use \Magento\Checkout\Model\Session as CheckoutSession;

class Subscribe extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var SubscriberFactory
     */
    protected $subscriberFactory;

    /**
     * @var Data
     */
    protected $dataHelper;

    /**
     * @param Context $context
     * @param CheckoutSession $checkoutSession
     * @param Session $customerSession
     * @param SubscriberFactory $subscriberFactory
     * @param Data $dataHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        CheckoutSession $checkoutSession,
        Session $customerSession,
        SubscriberFactory $subscriberFactory,
        Data $dataHelper,
        array $data = []
    ) {
        $this->checkoutSession   = $checkoutSession;
        $this->customerSession   = $customerSession;
        $this->subscriberFactory = $subscriberFactory;
        $this->dataHelper        = $dataHelper;

        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Get banner text
     *
     * @return mixed
     */
    public function getBannerText()
    {
        return $this->dataHelper->getConfigValue(Data::CONFIG_PATH_ECOMMERCE_SUCCSESS_BANNER_TEXT);
    }

    /**
     * Get button text
     *
     * @return mixed
     */
    public function getButtonText()
    {
        return $this->dataHelper->getConfigValue(Data::CONFIG_PATH_ECOMMERCE_SUCCSESS_BUTTON_TEXT);
    }

    /**
     * Produce and return block's html output
     *
     * @return string
     */
    public function toHtml()
    {
        if ($this->customerSession->isLoggedIn()
            || $this->isSubscribed()
            || !$this->dataHelper->getConfigValue(Data::CONFIG_PATH_ECOMMERCE_SUCCSESS_PAGE_SUBSCRIBE)
        ) {

            return '';
        }
        return parent::toHtml();
    }

    /**
     * Check if is subscribed
     *
     * @return bool
     */
    protected function isSubscribed()
    {
        $email = $this->getEmail();

        $subscriber = $this->subscriberFactory->create()->loadByEmail($email);

        return (bool)$subscriber->getId();
    }

    /**
     * Get customer's email
     *
     * @return float|string|null
     */
    public function getEmail()
    {
        return $this->checkoutSession->getLastRealOrder()->getCustomerEmail();
    }
}
