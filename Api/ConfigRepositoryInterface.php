<?php

namespace Mailjet\Mailjet\Api;

interface ConfigRepositoryInterface
{
    /**
     * Save config.
     *
     * @param  \Mailjet\Mailjet\Api\Data\ConfigInterface $config
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Mailjet\Mailjet\Api\Data\ConfigInterface $config);

    /**
     * Retrieve config.
     *
     * @param  int $configId
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($configId);

    /**
     * Retrieve configs matching the specified criteria.
     *
     * @param  \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Get first result from criteria
     *
     * @param  \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function getFirstResult(\Magento\Framework\Api\SearchCriteriaInterface $criteria);

    /**
     * Delete config.
     *
     * @param  \Mailjet\Mailjet\Api\Data\ConfigInterface $config
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Mailjet\Mailjet\Api\Data\ConfigInterface $config);

    /**
     * Delete config by ID.
     *
     * @param  int $configId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($configId);

    /**
     * Load Block data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface[] $configs
     */
    public function getAll();

    /**
     * Get config by StoreId
     *
     * @param  Int $storeId
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function getByStoreId($storeId);

    /**
     * Get unique configs for events
     *
     * @return \Mailjet\Mailjet\Model\ResourceModel\Config\Collection
     */
    public function getUniqueEventConfigs();

    /**
     * Get unique configs for ecommerce
     *
     * @return \Mailjet\Mailjet\Model\ResourceModel\Config\Collection
     */
    public function getUniqueEcommerceConfigs();

    /**
     * Generate Configs
     *
     * @param  \Magento\Store\Api\Data\StoreInterface[]|Array|null $stores
     * @return Void
     */
    public function generateConfigs($stores = null);
}
