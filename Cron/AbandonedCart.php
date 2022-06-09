<?php

namespace Mailjet\Mailjet\Cron;

use Mailjet\Mailjet\Helper\Data;

class AbandonedCart
{
    /**
     * @var Data
     */
    private $dataHelper;

    /**
     * @var \Mailjet\Mailjet\Model\Api\Email
     */
    private $apiEmail;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var \Mailjet\Mailjet\Api\ConfigRepositoryInterface
     */
    private $configRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $timezone;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    private $emulation;

    /**
     * AbandonedCart constructor.
     *
     * @param Data                                                 $dataHelper
     * @param \Mailjet\Mailjet\Model\Api\Email                     $apiEmail
     * @param \Magento\Quote\Api\CartRepositoryInterface           $cartRepository
     * @param \Mailjet\Mailjet\Api\ConfigRepositoryInterface       $configRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder         $searchCriteriaBuilder
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Magento\Store\Model\App\Emulation                   $emulation
     */
    public function __construct(
        Data $dataHelper,
        \Mailjet\Mailjet\Model\Api\Email $apiEmail,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository,
        \Mailjet\Mailjet\Api\ConfigRepositoryInterface $configRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Store\Model\App\Emulation $emulation
    ) {
        $this->dataHelper            = $dataHelper;
        $this->apiEmail              = $apiEmail;
        $this->cartRepository        = $cartRepository;
        $this->configRepository      = $configRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->timezone              = $timezone;
        $this->emulation             = $emulation;
    }

    /**
     * Execute action
     *
     * @return void
     */
    public function execute()
    {
        foreach ($this->configRepository->getAll() as $config) {
            $storeId = $config->getStoreId();

            if ($this->dataHelper->getConfigValue(
                Data::CONFIG_PATH_ABANDONED_CART_ABANDONED_CART_STATUS,
                $storeId
            )
                && $this->dataHelper->getConfigValue(
                    Data::CONFIG_PATH_ABANDONED_CART_ABANDONED_CART_TEMPLATE_ID,
                    $storeId
                )
            ) {
                $this->emulation->startEnvironmentEmulation($storeId);

                $sendingTime = $this->dataHelper->getConfigValue(
                    Data::CONFIG_PATH_ABANDONED_CART_ABANDONED_CART_TIME,
                    $storeId
                );
                $sendingTimeType = $this->dataHelper->getConfigValue(
                    Data::CONFIG_PATH_ABANDONED_CART_ABANDONED_CART_TIME_TYPE,
                    $storeId
                );
                $fromTime = $this->timezone->date(null, null, false)
                    ->modify('-' . $sendingTime . ' ' . $sendingTimeType)->modify('-5 minute')
                    ->format('Y-m-d H:i:s');
                $toTime = $this->timezone->date(null, null, false)
                    ->modify('-' . $sendingTime . ' ' . $sendingTimeType)->format('Y-m-d H:i:s');

                $filter = $this->searchCriteriaBuilder
                    ->addFilter('customer_id', true, 'notnull')
                    ->addFilter('items_count', 0, 'neq')
                    ->addFilter('is_active', 1, 'eq')
                    ->addFilter('store_id', $storeId, 'eq')
                    ->addFilter('customer_email', true, 'notnull')
                    ->addFilter('updated_at', $fromTime, 'gteq')
                    ->addFilter('updated_at', $toTime, 'lteq')
                    ->create();

                $carts = $this->cartRepository->getList($filter);

                if ($carts->getTotalCount()) {
                    $this->apiEmail->notifyAbandonedCart($carts->getItems(), $storeId);
                }

                $this->emulation->stopEnvironmentEmulation();
            }
        }
    }
}
