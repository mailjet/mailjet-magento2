<?php

namespace Mailjet\Mailjet\Model\System\Config\Backend;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;
use Magento\Newsletter\Model\Subscriber;
use Magento\Store\Model\StoreManagerInterface;
use Mailjet\Mailjet\Api\ConfigRepositoryInterface;
use Mailjet\Mailjet\Api\Data\ConfigInterfaceFactory;
use Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface;
use Mailjet\Mailjet\Helper\Data;
use Magento\Store\Model\ScopeInterface;
use Mailjet\Mailjet\Model\Api\Connection;
use Mailjet\Mailjet\Model\JobRepository;

class MailjetList extends Value
{
    /**
     * @var \Mailjet\Mailjet\Api\Data\ConfigInterfaceFactory
     */
    protected $configFactory;

    /**
     * @var \Mailjet\Mailjet\Model\Api\Connection
     */
    protected $apiConnection;
    /**
     * @var \Mailjet\Mailjet\Api\ConfigRepositoryInterface
     */
    protected $configRepository;
    /**
     * @var \Mailjet\Mailjet\Model\JobRepository
     */
    private $jobRepository;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface
     */
    private $subscriberQueueRepository;
    /**
     * @var \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory
     */
    private $subscriberCollectionFactory;
    /**
     * @var \Mailjet\Mailjet\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Mailjet\Mailjet\Api\ConfigRepositoryInterface $configRepository
     * @param \Mailjet\Mailjet\Model\Api\Connection $apiConnection
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterfaceFactory $configFactory
     * @param \Mailjet\Mailjet\Model\JobRepository $jobRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface $subscriberQueueRepository
     * @param \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory $subscriberCollectionFactory
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context                            $context,
        Registry                           $registry,
        ScopeConfigInterface               $config,
        TypeListInterface                  $cacheTypeList,
        ConfigRepositoryInterface          $configRepository,
        Connection                         $apiConnection,
        ConfigInterfaceFactory             $configFactory,
        JobRepository                      $jobRepository,
        StoreManagerInterface              $storeManager,
        SubscriberQueueRepositoryInterface $subscriberQueueRepository,
        CollectionFactory                  $subscriberCollectionFactory,
        Data                               $dataHelper,
        AbstractResource                   $resource = null,
        AbstractDb                         $resourceCollection = null,
        array                              $data = []
    ) {
        $this->configRepository = $configRepository;
        $this->apiConnection = $apiConnection;
        $this->configFactory = $configFactory;
        $this->jobRepository = $jobRepository;
        $this->storeManager = $storeManager;
        $this->subscriberQueueRepository = $subscriberQueueRepository;
        $this->subscriberCollectionFactory = $subscriberCollectionFactory;
        $this->dataHelper = $dataHelper;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * After Save
     *
     * @return \Mailjet\Mailjet\Model\System\Config\Backend\MailjetList
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterSave()
    {
        $syncPreference = $this->getFieldsetDataValue('sync_preference');
        $websiteId = $this->getScopeId();

        if ($this->getValue() && $this->isValueChanged() && $this->checkResyncAll($websiteId, $syncPreference)) {

            $configParams = $this->getConfigParams($websiteId);
            $config = $this->configFactory->create();
            $config->setApiKey($configParams['apiKey'])
                ->setSecretKey($configParams['secretKey'])
                ->setList($configParams['list'])
                ->setSyncPreference($syncPreference)
                ->setStoreId(0)
                ->setEcommerceData($configParams['ecommerceData'])
                ->setDeleted(1);

            $this->configRepository->save($config);

            if ($config->getId()) {
                $collection = $this->subscriberCollectionFactory->create();
                if ($websiteId) {
                    $website = $this->storeManager->getWebsite($websiteId);
                    $collection->addStoreFilter($website->getStoreIds());
                }
                foreach ($collection as $subscriber) {
                    if ($subscriber->getSubscriberStatus() == Subscriber::STATUS_SUBSCRIBED) {
                        $this->subscriberQueueRepository->subscribe($subscriber, $config);
                    } else {
                        $this->subscriberQueueRepository->unsubscribe($subscriber, $config);
                    }
                }

                $this->jobRepository->generateJobs([$config]);
            }

        }
        return parent::afterSave();
    }

    /**
     * Check Resync All
     *
     * @param string|null $websiteId
     * @param int|null $syncPreference
     * @return bool|mixed
     */
    private function checkResyncAll($websiteId, $syncPreference)
    {
        if ($websiteId) {
            $isRsyncAll = $this->dataHelper->getConfigValue(
                Data::CONFIG_PATH_ECOMMERCE_DATA,
                $websiteId,
                ScopeInterface::SCOPE_WEBSITES
            );
        } else {
            $isRsyncAll = $syncPreference == Data::SYNC_PREFERENCE_ALL ||
            $this->dataHelper->getConfigValue(
                data::CONFIG_PATH_ACCOUNT_SYNC_PREFERENCE
            ) == Data::SYNC_PREFERENCE_ALL;
        }
        return $isRsyncAll;
    }

    /**
     * Get Params from configuration
     *
     * @param string|null $websiteId
     * @return array
     */
    private function getConfigParams($websiteId)
    {
        $configParams = [];
        $configParams['list'] = $this->getFieldsetDataValue('mailjet_list');
        if ($websiteId) {
            $configParams['secretKey'] = $this->dataHelper->getConfigValue(
                Data::CONFIG_PATH_ACCOUNT_SECRET_KEY,
                $websiteId,
                ScopeInterface::SCOPE_WEBSITES
            );
            $configParams['ecommerceData'] = $this->dataHelper->getConfigValue(
                Data::CONFIG_PATH_ECOMMERCE_DATA,
                $websiteId,
                ScopeInterface::SCOPE_WEBSITES
            );
            $configParams['apiKey'] = $this->dataHelper->getConfigValue(
                Data::CONFIG_PATH_ACCOUNT_API_KEY,
                $websiteId,
                ScopeInterface::SCOPE_WEBSITES
            );

        } else {
            $configParams['secretKey'] = $this->dataHelper->getConfigValue(Data::CONFIG_PATH_ACCOUNT_SECRET_KEY);
            $configParams['ecommerceData'] = $this->dataHelper->getConfigValue(Data::CONFIG_PATH_ECOMMERCE_DATA);
            $configParams['apiKey'] = $this->dataHelper->getConfigValue(Data::CONFIG_PATH_ACCOUNT_API_KEY);
        }
        return $configParams;
    }
}
