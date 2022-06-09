<?php

namespace Mailjet\Mailjet\Model;

use Mailjet\Mailjet\Api\Data\ErrorInterface as DataInterface;
use Mailjet\Mailjet\Model\ResourceModel\Error as Resource;
use Mailjet\Mailjet\Model\ErrorFactory as ModelFactory;
use Mailjet\Mailjet\Model\ResourceModel\Error\CollectionFactory as CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class ErrorRepository implements \Mailjet\Mailjet\Api\ErrorRepositoryInterface
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
     * ErrorRepository construct
     *
     * @param \Mailjet\Mailjet\Model\ResourceModel\Error $resource
     * @param \Mailjet\Mailjet\Model\ErrorFactory $modelFactory
     * @param \Mailjet\Mailjet\Model\ResourceModel\Error\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        Resource $resource,
        ModelFactory $modelFactory,
        CollectionFactory $collectionFactory,
        \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource             = $resource;
        $this->modelFactory         = $modelFactory;
        $this->collectionFactory    = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor  = $collectionProcessor;
    }

    /**
     * Save error data
     *
     * @param  DataInterface $error
     * @return DataInterface
     * @throws CouldNotSaveException
     */
    public function save(DataInterface $error)
    {
        try {
            $this->resource->save($error);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $error;
    }

    /**
     * Load Error data by given Error Identity
     *
     * @param  Int $errorId
     * @return DataInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($errorId)
    {
        $error = $this->modelFactory->create();
        $this->resource->load($error, $errorId);
        if (!$error->getId()) {
            throw new NoSuchEntityException(__('The error with the "%1" ID doesn\'t exist.', $errorId));
        }
        return $error;
    }

    /**
     * Load Error data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param                                        \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return                                       \Magento\Framework\Api\SearchResultsInterface
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
     * Delete error
     *
     * @param  DataInterface $error
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(DataInterface $error)
    {
        try {
            $this->resource->delete($error);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete error by given error Identity
     *
     * @param  Int $errorId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($errorId)
    {
        return $this->delete($this->getById($errorId));
    }
}
