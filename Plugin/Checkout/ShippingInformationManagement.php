<?php

namespace Mailjet\Mailjet\Plugin\Checkout;

class ShippingInformationManagement
{
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @var \Mailjet\Mailjet\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Mailjet\Mailjet\Helper\Data $dataHelper
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->dataHelper      = $dataHelper;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        if ($this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ECOMMERCE_CHECKOUT_PAGE_SUBSCRIBE)) {
            $extensionAttributes = $addressInformation->getExtensionAttributes();
            $newsletterSubscribe = (int)$extensionAttributes->getNewsletterSubscribe();

            $quote = $this->quoteRepository->getActive($cartId);
            $quote->setNewsletterSubscribe($newsletterSubscribe);
        }
    }
}
