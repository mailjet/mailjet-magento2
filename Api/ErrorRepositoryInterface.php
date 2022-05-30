<?php

namespace Mailjet\Mailjet\Api;

interface ErrorRepositoryInterface
{
    /**
     * Save error.
     *
     * @param  \Mailjet\Mailjet\Api\Data\ErrorInterface $error
     * @return \Mailjet\Mailjet\Api\Data\ErrorInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Mailjet\Mailjet\Api\Data\ErrorInterface $error);

    /**
     * Retrieve error.
     *
     * @param  int $errorId
     * @return \Mailjet\Mailjet\Api\Data\ErrorInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($errorId);

    /**
     * Retrieve errors matching the specified criteria.
     *
     * @param  \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete error.
     *
     * @param  \Mailjet\Mailjet\Api\Data\ErrorInterface $error
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Mailjet\Mailjet\Api\Data\ErrorInterface $error);

    /**
     * Delete error by ID.
     *
     * @param  int $errorId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($errorId);
}
