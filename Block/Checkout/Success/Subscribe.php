<?php

namespace Mailjet\Mailjet\Block\Checkout\Success;

class Subscribe extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    protected $subscriberFactory;

    /**
     * @var \Mailjet\Mailjet\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Mailjet\Mailjet\Helper\Data $dataHelper,
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

    public function getBannerText()
    {
        return $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ECOMMERCE_SUCCSESS_BANNER_TEXT);
    }

    public function getButtonText()
    {
        return $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ECOMMERCE_SUCCSESS_BUTTON_TEXT);
    }

    /**
     * {@inheritdoc}
     */
    public function toHtml()
    {
        if ($this->customerSession->isLoggedIn()
            || $this->isSubscribed()
            || !$this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ECOMMERCE_SUCCSESS_PAGE_SUBSCRIBE)
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

    public function getEmail()
    {
        return $this->checkoutSession->getLastRealOrder()->getCustomerEmail();
    }
}
