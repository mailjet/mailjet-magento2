<?php

namespace Mailjet\Mailjet\Cron;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Wishlist\Model\ResourceModel\Wishlist\CollectionFactory;
use Mailjet\Mailjet\Helper\Data;
use Mailjet\Mailjet\Model\Api\Email;

class Wishlist
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Data
     */
    private $dataHelper;

    /**
     * @var CollectionFactory
     */
    private $wishlistCollectionFactory;

    /**
     * @var Email
     */
    private $apiEmail;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @var Emulation
     */
    private $emulation;

    /**
     * Wishlist constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param Data $dataHelper
     * @param CollectionFactory $wishlistCollectionFactory
     * @param Email $apiEmail
     * @param CustomerRepositoryInterface $customerRepository
     * @param TimezoneInterface $timezone
     * @param Emulation $emulation
     */
    public function __construct(
        StoreManagerInterface       $storeManager,
        Data                        $dataHelper,
        CollectionFactory           $wishlistCollectionFactory,
        Email                       $apiEmail,
        CustomerRepositoryInterface $customerRepository,
        TimezoneInterface           $timezone,
        Emulation                   $emulation
    ) {
        $this->storeManager = $storeManager;
        $this->dataHelper = $dataHelper;
        $this->wishlistCollectionFactory = $wishlistCollectionFactory;
        $this->apiEmail = $apiEmail;
        $this->customerRepository = $customerRepository;
        $this->timezone = $timezone;
        $this->emulation = $emulation;
    }

    /**
     * Execute
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        foreach ($this->storeManager->getWebsites() as $website) {
            $storeId = $website->getDefaultStore()->getId();

            if ($this->dataHelper->getConfigValue(
                Data::CONFIG_PATH_WISHLIST_NOTIFICATIONS_WISHLIST_REMINDER_STATUS,
                $storeId
            )
                && $this->dataHelper->getConfigValue(
                    Data::CONFIG_PATH_WISHLIST_NOTIFICATIONS_WISHLIST_REMINDER_TEMPLATE_ID,
                    $storeId
                )
            ) {
                $this->emulation->startEnvironmentEmulation($storeId);

                $fromTime = $this->timezone->date(null, null, false)
                    ->modify('-1 week')->modify('-5 minute')->format('Y-m-d H:i:s');
                $toTime = $this->timezone->date(null, null, false)
                    ->modify('-1 week')->format('Y-m-d H:i:s');

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
