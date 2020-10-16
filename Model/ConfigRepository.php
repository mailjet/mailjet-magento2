<?php

namespace Mailjet\Mailjet\Model;

use Mailjet\Mailjet\Api\Data\ConfigInterface as DataInterface;
use Mailjet\Mailjet\Model\ResourceModel\Config as Resource;
use Mailjet\Mailjet\Model\ConfigFactory as ModelFactory;
use Mailjet\Mailjet\Model\ResourceModel\Config\CollectionFactory as CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class ConfigRepository implements \Mailjet\Mailjet\Api\ConfigRepositoryInterface
{
    /**
     * @var Resource
     */
    protected $resource;

    /**
     * @var ModelFactory
     */
    protected $modelFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Framework\Api\SearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \Mailjet\Mailjet\Helper\Data
     */
    private $helperData;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param Resource $resource
     * @param ModelFactory $modelFactory
     * @param CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory,
     * @param CollectionProcessorInterface $collectionProcessor
     * @param \Mailjet\Mailjet\Helper\Data $helperData
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Resource $resource,
        ModelFactory $modelFactory,
        CollectionFactory $collectionFactory,
        \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        \Mailjet\Mailjet\Helper\Data $helperData,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->resource              = $resource;
        $this->modelFactory          = $modelFactory;
        $this->collectionFactory     = $collectionFactory;
        $this->searchResultsFactory  = $searchResultsFactory;
        $this->collectionProcessor   = $collectionProcessor;
        $this->helperData            = $helperData;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager          = $storeManager;
    }

    /**
     * Save Config data
     *
     * @param DataInterface $config
     * @return DataInterface
     * @throws CouldNotSaveException
     */
    public function save(DataInterface $config)
    {
        try {
            $this->resource->save($config);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $config;
    }

    /**
     * Load Config data by given Config Identity
     *
     * @param Int $configId
     * @return DataInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($configId)
    {
        $config = $this->modelFactory->create();
        $this->resource->load($config, $configId);
        if (!$config->getId()) {
            throw new NoSuchEntityException(__('The config with the "%1" ID doesn\'t exist.', $configId));
        }
        return $config;
    }

    /**
     * Load Config data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * Get first result from criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function getFirstResult(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        return $collection->getFirstItem();
    }

    /**
     * Delete Config
     *
     * @param DataInterface $config
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(DataInterface $config)
    {
        try {
            $this->resource->delete($config);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Config by given Config Identity
     *
     * @param Int $configId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($configId)
    {
        return $this->delete($this->getById($configId));
    }

    /**
     * Load Block data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface[] $configs
     */
    public function getAll()
    {
        $filter = $this->searchCriteriaBuilder
            ->addFilter(DataInterface::DELETED, true, 'null')
            ->create();

        $list = $this->getList($filter);
        return $list->getItems();
    }

    /**
     * Get config by StoreId
     *
     * @param Int $storeId
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function getByStoreId($storeId)
    {
        $filter = $this->searchCriteriaBuilder
            ->addFilter(DataInterface::STORE_ID, $storeId, 'eq')
            ->addFilter(DataInterface::DELETED, true, 'null')
            ->create();

        return $this->getFirstResult($filter);
    }

    /**
     * Get unique configs for events
     *
     * @return \Mailjet\Mailjet\Model\ResourceModel\Config\Collection
     */
    public function getUniqueEventConfigs()
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('deleted', ['null' => true])
            ->addGroups([
                DataInterface::API_KEY,
                DataInterface::SECRET_KEY,
                DataInterface::ENABLED,
                DataInterface::UNSUBSCRIBE_EVENT
            ])
            ->setOrder(DataInterface::ENABLED, \Magento\Framework\Data\Collection::SORT_ORDER_ASC)
            ->setOrder(DataInterface::UNSUBSCRIBE_EVENT, \Magento\Framework\Data\Collection::SORT_ORDER_ASC);

        return $collection;
    }

    /**
     * Get unique configs for ecommerce
     *
     * @return \Mailjet\Mailjet\Model\ResourceModel\Config\Collection
     */
    public function getUniqueEcommerceConfigs()
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('deleted', ['null' => true])
            ->addGroups([
                DataInterface::API_KEY,
                DataInterface::SECRET_KEY,
                DataInterface::ENABLED,
                DataInterface::ECOMMERCE_DATA
            ])
            ->setOrder(DataInterface::ENABLED, \Magento\Framework\Data\Collection::SORT_ORDER_ASC)
            ->setOrder(DataInterface::ECOMMERCE_DATA, \Magento\Framework\Data\Collection::SORT_ORDER_ASC);

        return $collection;
    }

    /**
     * Generate Configs
     *
     * @param \Magento\Store\Api\Data\StoreInterface[] | Array | null $stores
     * @return Void
     */
    public function generateConfigs($stores = null)
    {
        if ($stores === null) {
            $stores = $this->storeManager->getStores();
        }

        foreach ($stores as $store) {
            if ($store instanceof \Magento\Store\Api\Data\StoreInterface) {
                $storeId = $store->getId();
            } else {
                $storeId = $store;
            }

            $api_key = $this->helperData->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ACCOUNT_API_KEY, $storeId);
            $secret_key = $this->helperData->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ACCOUNT_SECRET_KEY, $storeId);
            $mailjet_list = $this->helperData->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ACCOUNT_MAILJET_LIST, $storeId);
            $sync_preference = $this->helperData->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ACCOUNT_SYNC_PREFERENCE, $storeId);
            $active = $this->helperData->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ACCOUNT_ACTIVE, $storeId);
            $unsubscribe_event = $this->helperData->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ACCOUNT_UNSUBSCRIBE_EVENT, $storeId);
            $ecommerce_data = $this->helperData->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ECOMMERCE_DATA, $storeId);

            $filter = $this->searchCriteriaBuilder
                ->addFilter(DataInterface::API_KEY, $api_key, 'eq')
                ->addFilter(DataInterface::SECRET_KEY, $secret_key, 'eq')
                ->addFilter(DataInterface::LIST, $mailjet_list, 'eq')
                ->addFilter(DataInterface::SYNC_PREFERENCE, $sync_preference, 'eq')
                ->addFilter(DataInterface::STORE_ID, $storeId, 'eq')
                ->addFilter(DataInterface::ENABLED, $active, 'eq')
                ->addFilter(DataInterface::UNSUBSCRIBE_EVENT, $unsubscribe_event, 'eq')
                ->addFilter(DataInterface::ECOMMERCE_DATA, $ecommerce_data, 'eq')
                ->addFilter(DataInterface::DELETED, true, 'null')
                ->create();

            $list = $this->getList($filter);

            if (!$list->getTotalCount()) {
                $filterNotDeleted = $this->searchCriteriaBuilder
                    ->addFilter(DataInterface::STORE_ID, $storeId, 'eq')
                    ->addFilter(DataInterface::DELETED, true, 'null')
                    ->create();

                $forDeletion = $this->getList($filterNotDeleted);

                foreach ($forDeletion->getItems() as $config) {
                    $config->setDeleted(1);
                    $this->save($config);
                }

                $newConfig = $this->modelFactory->create();
                $newConfig->setApiKey($api_key)
                    ->setSecretKey($secret_key)
                    ->setList($mailjet_list)
                    ->setSyncPreference($sync_preference)
                    ->setStoreId($storeId)
                    ->setUnsubscribeEvent($unsubscribe_event)
                    ->setEcommerceData($ecommerce_data)
                    ->setEnabled($active);

                $this->save($newConfig);
            }
        }
    }
}
