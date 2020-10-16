<?php

namespace Mailjet\Mailjet\Cron;

class Wishlist
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Mailjet\Mailjet\Helper\Data
     */
    private $dataHelper;

    /**
     * @var \Magento\Wishlist\Model\ResourceModel\Wishlist\CollectionFactory
     */
    private $wishlistCollectionFactory;

    /**
     * @var \Mailjet\Mailjet\Model\Api\Email
     */
    private $apiEmail;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $timezone;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    private $emulation;

    /**
     * Wishlist constructor.
     *
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     * @param \Magento\Wishlist\Model\ResourceModel\Wishlist\CollectionFactory $wishlistCollectionFactory
     * @param \Mailjet\Mailjet\Model\Api\Email $apiEmail
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Magento\Store\Model\App\Emulation $emulation
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Mailjet\Mailjet\Helper\Data $dataHelper,
        \Magento\Wishlist\Model\ResourceModel\Wishlist\CollectionFactory $wishlistCollectionFactory,
        \Mailjet\Mailjet\Model\Api\Email $apiEmail,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Store\Model\App\Emulation $emulation
    ) {
        $this->storeManager              = $storeManager;
        $this->dataHelper                = $dataHelper;
        $this->wishlistCollectionFactory = $wishlistCollectionFactory;
        $this->apiEmail                  = $apiEmail;
        $this->customerRepository        = $customerRepository;
        $this->timezone                  = $timezone;
        $this->emulation                 = $emulation;
    }

    public function execute()
    {
        foreach ($this->storeManager->getWebsites() as $website) {
            $storeId = $website->getDefaultStore()->getId();

            if ($this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_WISHLIST_NOTIFICATIONS_WISHLIST_REMINDER_STATUS, $storeId)
                && $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_WISHLIST_NOTIFICATIONS_WISHLIST_REMINDER_TEMPLATE_ID, $storeId)
            ) {
                $this->emulation->startEnvironmentEmulation($storeId);

                $fromTime = $this->timezone->date(null, null, false)->modify('-1 week')->modify('-5 minute')->format('Y-m-d H:i:s');
                $toTime = $this->timezone->date(null, null, false)->modify('-1 week')->format('Y-m-d H:i:s');

                $wishlists = $this->wishlistCollectionFactory->create()
                    ->addFieldToFilter('updated_at', ['gteq' => $fromTime])
                    ->addFieldToFilter('updated_at', ['lteq' => $toTime]);

                foreach ($wishlists->getItems() as $wishlist) {
                    if ($wishlist->getItemsCount()) {
                        $customer = $this->customerRepository->getById($wishlist->getCustomerId());
                        $productIds = [];

                        foreach ($wishlist->getItemCollection() as $item) {
                            $productIds[] = $item->getProductId();
                        }

                        $this->apiEmail->remindWishlist($customer, $productIds, $storeId);
                    }
                }

                $this->emulation->stopEnvironmentEmulation();
            }
        }
    }
}
