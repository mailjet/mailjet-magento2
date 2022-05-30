<?php

namespace Mailjet\Mailjet\Plugin\Checkout;

use Magento\Quote\Model\QuoteRepository;
use Mailjet\Mailjet\Helper\Data;

class ShippingInformationManagement
{
    /**
     * @var QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @var Data
     */
    protected $dataHelper;

    /**
     * @param QuoteRepository $quoteRepository
     * @param Data $dataHelper
     */
    public function __construct(
        QuoteRepository $quoteRepository,
        Data            $dataHelper
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->dataHelper = $dataHelper;
    }

    /**
     * Before save address information
     *
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param int $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement   $subject,
        int $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        if ($this->dataHelper->getConfigValue(Data::CONFIG_PATH_ECOMMERCE_CHECKOUT_PAGE_SUBSCRIBE)) {
            $extensionAttributes = $addressInformation->getExtensionAttributes();
            $newsletterSubscribe = (int)$extensionAttributes->getNewsletterSubscribe();

            $quote = $this->quoteRepository->getActive($cartId);
            $quote->setNewsletterSubscribe($newsletterSubscribe);
        }
    }
}
