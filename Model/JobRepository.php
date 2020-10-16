<?php

namespace Mailjet\Mailjet\Model;

use Mailjet\Mailjet\Api\Data\JobInterface as DataInterface;
use Mailjet\Mailjet\Model\ResourceModel\Job as Resource;
use Mailjet\Mailjet\Model\JobFactory as ModelFactory;
use Mailjet\Mailjet\Model\ResourceModel\Job\CollectionFactory as CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Mailjet\Mailjet\Api\Data\SubscriberQueueInterface as SubscriberQueue;

class JobRepository implements \Mailjet\Mailjet\Api\JobRepositoryInterface
{
    /**
     * Limit the amount of subscribers executed at once
     */
    const SUBSCRIBER_QUEUE_LIMIT = 100;

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
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Mailjet\Mailjet\Api\ConfigRepositoryInterface
     */
    private $configRepository;

    /**
     * @var \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface
     */
    private $subscriberQueueRepository;

    /**
     * @var \Mailjet\Mailjet\Api\Data\ErrorInterfaceFactory
     */
    private $errorFactory;

    /**
     * @var \Mailjet\Mailjet\Api\ErrorRepositoryInterface
     */
    private $errorRepository;

    /**
     * @var \Mailjet\Mailjet\Model\Api\Connection
     */
    private $apiConnection;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateTime;

    /**
     * @var \Mailjet\Mailjet\Model\Api\Email
     */
    private $apiEmail;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    private $emulation;

    /**
     * @param Resource $resource
     * @param ModelFactory $modelFactory
     * @param CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory,
     * @param CollectionProcessorInterface $collectionProcessor
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Mailjet\Mailjet\Api\ConfigRepositoryInterface $configRepository
     * @param \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface $subscriberQueueRepository
     * @param \Mailjet\Mailjet\Api\Data\ErrorInterfaceFactory $errorFactory
     * @param \Mailjet\Mailjet\Api\ErrorRepositoryInterface $errorRepository
     * @param \Mailjet\Mailjet\Model\Api\Connection $apiConnection
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Mailjet\Mailjet\Model\Api\Email $apiEmail
     * @param \Magento\Store\Model\App\Emulation $emulation
     */
    public function __construct(
        Resource $resource,
        ModelFactory $modelFactory,
        CollectionFactory $collectionFactory,
        \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Mailjet\Mailjet\Api\ConfigRepositoryInterface $configRepository,
        \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface $subscriberQueueRepository,
        \Mailjet\Mailjet\Api\Data\ErrorInterfaceFactory $errorFactory,
        \Mailjet\Mailjet\Api\ErrorRepositoryInterface $errorRepository,
        \Mailjet\Mailjet\Model\Api\Connection $apiConnection,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Mailjet\Mailjet\Model\Api\Email $apiEmail,
        \Magento\Store\Model\App\Emulation $emulation
    ) {
        $this->resource                  = $resource;
        $this->modelFactory              = $modelFactory;
        $this->collectionFactory         = $collectionFactory;
        $this->searchResultsFactory      = $searchResultsFactory;
        $this->collectionProcessor       = $collectionProcessor;
        $this->searchCriteriaBuilder     = $searchCriteriaBuilder;
        $this->configRepository          = $configRepository;
        $this->subscriberQueueRepository = $subscriberQueueRepository;
        $this->errorFactory              = $errorFactory;
        $this->errorRepository           = $errorRepository;
        $this->apiConnection             = $apiConnection;
        $this->dateTime                  = $dateTime;
        $this->apiEmail                  = $apiEmail;
        $this->emulation                 = $emulation;
    }

    /**
     * Save Job data
     *
     * @param DataInterface $job
     * @return DataInterface
     * @throws CouldNotSaveException
     */
    public function save(DataInterface $job)
    {
        try {
            $this->resource->save($job);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $job;
    }

    /**
     * Load Job data by given Job Identity
     *
     * @param Int $jobId
     * @return DataInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($jobId)
    {
        $job = $this->modelFactory->create();
        $this->resource->load($job, $jobId);
        if (!$job->getId()) {
            throw new NoSuchEntityException(__('The job with the "%1" ID doesn\'t exist.', $jobId));
        }
        return $job;
    }

    /**
     * Load Job data collection by given search criteria
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
     * Delete Job
     *
     * @param DataInterface $job
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(DataInterface $job)
    {
        try {
            $this->resource->delete($job);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Job by given Job Identity
     *
     * @param Int $jobId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($jobId)
    {
        return $this->delete($this->getById($jobId));
    }

    /**
     * Generate Jobs
     *
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterface[] | Array | null $configs
     * @return Void
     */
    public function generateJobs($configs = null)
    {
        if ($configs === null) {
            $configs = $this->configRepository->getAll();
        }

        foreach ($configs as $config) {
            if ($config instanceof \Mailjet\Mailjet\Api\Data\ConfigInterface) {
                $configId = $config->getId();
            } else {
                $configId = $config;
            }

            foreach (\Mailjet\Mailjet\Model\SubscriberQueue::ACTIONS as $action) {
                $filter = $this->searchCriteriaBuilder
                    ->addFilter(SubscriberQueue::ACTION, $action, 'eq')
                    ->addFilter(SubscriberQueue::CONFIG_ID, $configId, 'eq')
                    ->addFilter(SubscriberQueue::JOB_ID, true, 'null')
                    ->setPageSize(self::SUBSCRIBER_QUEUE_LIMIT)
                    ->create();

                $list = $this->subscriberQueueRepository->getList($filter);

                if ($list->getTotalCount()) {
                    $job = $this->modelFactory->create();

                    $job->setAction($action)
                        ->setConfigId($configId);

                    $this->save($job);

                    foreach ($list->getItems() as $item) {
                        $item->setJobId($job->getId());
                        $this->subscriberQueueRepository->save($item);
                    }
                }
            }
        }
    }

    /**
     * Execute Jobs
     *
     * @param \Mailjet\Mailjet\Api\Data\JobInterface[] $job
     * @return Void
     */
    public function executeJob($job)
    {
        $filter = $this->searchCriteriaBuilder
            ->addFilter(SubscriberQueue::JOB_ID, $job->getId(), 'eq')
            ->create();

        $queue = $this->subscriberQueueRepository->getList($filter);
        $config = $this->configRepository->getById($job->getConfigId());

        $customers = [];

        foreach ($queue->getItems() as $customer) {
            $customers[] = $customer->toArray();
        }

        if ($customers) {
            $this->emulation->startEnvironmentEmulation($config->getStoreId());

            $connection = $this->apiConnection->getConnection($config);
            $errors = [];

            if ($job->getAction() == \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface::ACTIONS['SUB']) {
                $connection->manageContacts($config->getList(), $customers, \Mailjet\Mailjet\Helper\MailjetAPI::CONTACT_ACTIONS['subscribe']);
            } elseif ($job->getAction() == \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface::ACTIONS['UNS']) {
                $connection->manageContacts($config->getList(), $customers, \Mailjet\Mailjet\Helper\MailjetAPI::CONTACT_ACTIONS['unsubscribe']);
            } elseif ($job->getAction() == \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface::ACTIONS['DEL']) {
                $connection->manageContacts($config->getList(), $customers, \Mailjet\Mailjet\Helper\MailjetAPI::CONTACT_ACTIONS['delete']);
            } elseif ($job->getAction() == \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface::ACTIONS['UPD']) {
                foreach ($customers as $customer) {
                    $connection->updateContactData($customer['email'], json_decode($customer['property'], true));

                    if (!$connection->getResponce()->success()) {
                        $error = $this->errorFactory->create();
                        $error->setApiResult(json_encode($connection->getResponce()->getBody()));
                        $error->setStatus($connection->getResponce()->getStatus());
                        $errors[] = $this->errorRepository->save($error);
                    }
                }
            } elseif ($job->getAction() == \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface::ACTIONS['STK']) {
                $this->apiEmail->notifyStock($customers, $connection, $config->getStoreId());
            } elseif ($job->getAction() == \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface::ACTIONS['SAL']) {
                $this->apiEmail->notifySale($customers, $connection, $config->getStoreId());
            }

            if (!$connection->getResponce()->success()) {
                $error = $this->errorFactory->create();
                $error->setApiResult(json_encode($connection->getResponce()->getBody()));
                $error->setStatus($connection->getResponce()->getStatus());
                $errors[] = $this->errorRepository->save($error)->getId();
            }

            if ($errors) {
                $job->setErrorId(implode(',', $errors));
                $job->setExecutedAt($this->dateTime->gmtDate());
            }

            $this->emulation->stopEnvironmentEmulation();
        }

        if ($job->getErrorId()) {
            $this->save($job);
            $config->setHasErrors(1);

            $this->configRepository->save($config);
        } else {
            $this->delete($job);
        }
    }

    /**
     * Execute All Jobs
     *
     * @return Void
     */
    public function executeAllJobs()
    {
        $filter = $this->searchCriteriaBuilder
            ->addFilter(DataInterface::ERROR_ID, true, 'null')
            ->create();

        $jobs = $this->getList($filter);

        foreach ($jobs->getItems() as $job) {
            $this->executeJob($job);
        }
    }
}
