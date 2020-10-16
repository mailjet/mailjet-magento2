<?php

namespace Mailjet\Mailjet\Observer\Catalog\Product;

class SaveBefore implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface
     */
    protected $subscriberQueueRepository;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Wishlist\Model\ResourceModel\Item\Collection
     */
    protected $wishlistItemCollection;

    /**
     * @var \Magento\Wishlist\Model\ResourceModel\Wishlist\CollectionFactory
     */
    protected $wishlistCollectionFactory;

    /**
     * @var \Mailjet\Mailjet\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Bundle\Model\Product\Type
     */
    protected $bundleType;

    /**
     * @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable
     */
    protected $configurableType;

    /**
     * @var \Magento\GroupedProduct\Model\Product\Type\Grouped
     */
    protected $groupedType;

    /**
     * Order Save After constructor.
     *
     * @param \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface $subscriberQueueRepository
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Wishlist\Model\ResourceModel\Item\Collection $wishlistItemCollection
     * @param \Magento\Wishlist\Model\ResourceModel\Wishlist\CollectionFactory $wishlistCollectionFactory
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     * @param \Magento\Bundle\Model\Product\Type $bundleType
     * @param \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableType
     * @param \Magento\GroupedProduct\Model\Product\Type\Grouped $groupedType
     */
    public function __construct(
        \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface $subscriberQueueRepository,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Wishlist\Model\ResourceModel\Item\Collection $wishlistItemCollection,
        \Magento\Wishlist\Model\ResourceModel\Wishlist\CollectionFactory $wishlistCollectionFactory,
        \Mailjet\Mailjet\Helper\Data $dataHelper,
        \Magento\Bundle\Model\Product\Type $bundleType,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableType,
        \Magento\GroupedProduct\Model\Product\Type\Grouped $groupedType
    ) {
        $this->subscriberQueueRepository  = $subscriberQueueRepository;
        $this->customerRepository         = $customerRepository;
        $this->wishlistItemCollection     = $wishlistItemCollection;
        $this->wishlistCollectionFactory  = $wishlistCollectionFactory;
        $this->dataHelper                 = $dataHelper;
        $this->bundleType                 = $bundleType;
        $this->configurableType           = $configurableType;
        $this->groupedType                = $groupedType;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return Void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
       /**
        * @var $product \Magento\Catalog\Model\Product
        */
        $product = $observer->getProduct();

        $origionalData = $product->getOrigData();

        if ($this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_WISHLIST_NOTIFICATIONS_ITEM_BACK_IN_STOCK_STATUS)
            && $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_WISHLIST_NOTIFICATIONS_ITEM_BACK_IN_STOCK_TEMPLATE_ID)
        ) {
            if (!empty($origionalData['extension_attributes']) && $origionalData['extension_attributes']->getStockItem()) {
                if (!$origionalData['extension_attributes']->getStockItem()->getIsInStock() && $product->isInStock()) {
                    $wishlists = $this->wishlistCollectionFactory->create()->join(
                        ['items' => $this->wishlistItemCollection->getMainTable()],
                        "items.wishlist_id = main_table.wishlist_id"
                    )->addFieldToFilter('product_id', ['eq' => $product->getId()]);

                    foreach ($wishlists->getItems() as $wishlist) {
                        $customer = $this->customerRepository->getById($wishlist->getCustomerId());
                        $this->subscriberQueueRepository->notifyStockStatus($customer, $product->getId());
                    }
                }
            }
        }

        if ($this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_WISHLIST_NOTIFICATIONS_ITEM_ON_SALE_STATUS)
            && $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_WISHLIST_NOTIFICATIONS_ITEM_ON_SALE_TEMPLATE_ID)
        ) {
            if ($product->isInStock() && empty($origionalData['special_price']) && $product->getSpecialPrice()) {
                $groupedIds = $this->groupedType->getParentIdsByChild($product->getId());
                $configurableIds = $this->configurableType->getParentIdsByChild($product->getId());
                $bundleIds = $this->bundleType->getParentIdsByChild($product->getId());
                $productIds = array_merge([$product->getId()], $groupedIds, $configurableIds, $bundleIds);

                $wishlists = $this->wishlistCollectionFactory->create()->join(
                    ['items' => $this->wishlistItemCollection->getMainTable()],
                    "items.wishlist_id = main_table.wishlist_id"
                )->addFieldToFilter('product_id', ['in' => $productIds]);

                foreach ($wishlists->getItems() as $wishlist) {
                    $customer = $this->customerRepository->getById($wishlist->getCustomerId());
                    $this->subscriberQueueRepository->notifySaleProduct($customer, $product->getId());
                }
            }
        }
    }
}
