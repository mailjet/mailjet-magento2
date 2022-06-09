<?php

namespace Mailjet\Mailjet\Model;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Newsletter\Model\Subscriber;
use Mailjet\Mailjet\Api\Data\ConfigInterface;
use Mailjet\Mailjet\Api\Data\SubscriberQueueInterface as DataInterface;
use Mailjet\Mailjet\Model\ResourceModel\SubscriberQueue as Resource;
use Mailjet\Mailjet\Model\SubscriberQueueFactory as ModelFactory;
use Mailjet\Mailjet\Model\ResourceModel\SubscriberQueue\CollectionFactory as CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class SubscriberQueueRepository implements \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface
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
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \Mailjet\Mailjet\Model\ConfigRepository
     */
    private $configRepository;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Mailjet\Mailjet\Model\Api\Ecommerce
     */
    private $apiEcommerce;

    /**
     * @param Resource                                             $resource
     * @param ModelFactory                                         $modelFactory
     * @param CollectionFactory                                    $collectionFactory
     * @param \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory
     * @param \Magento\Framework\Api\SearchCriteriaBuilder         $searchCriteriaBuilder
     * @param CollectionProcessorInterface                         $collectionProcessor
     * @param \Mailjet\Mailjet\Model\ConfigRepository              $configRepository
     * @param \Magento\Customer\Api\CustomerRepositoryInterface    $customerRepository
     * @param \Mailjet\Mailjet\Model\Api\Ecommerce                 $apiEcommerce
     */
    public function __construct(
        Resource $resource,
        ModelFactory $modelFactory,
        CollectionFactory $collectionFactory,
        \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        CollectionProcessorInterface $collectionProcessor,
        \Mailjet\Mailjet\Model\ConfigRepository $configRepository,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Mailjet\Mailjet\Model\Api\Ecommerce $apiEcommerce
    ) {
        $this->resource              = $resource;
        $this->modelFactory          = $modelFactory;
        $this->collectionFactory     = $collectionFactory;
        $this->searchResultsFactory  = $searchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionProcessor   = $collectionProcessor;
        $this->configRepository      = $configRepository;
        $this->customerRepository    = $customerRepository;
        $this->apiEcommerce          = $apiEcommerce;
    }

    /**
     * Save Subscriber Queue data
     *
     * @param  DataInterface $queue
     * @return DataInterface
     * @throws CouldNotSaveException
     */
    public function save(DataInterface $queue)
    {
        try {
            $this->resource->save($queue);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $queue;
    }

    /**
     * Load SubscriberQueue data by given SubscriberQueue Identity
     *
     * @param Int $queueId
     * @return DataInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $queueId)
    {
        $queue = $this->modelFactory->create();
        $this->resource->load($queue, $queueId);
        if (!$queue->getId()) {
            throw new NoSuchEntityException(__('The queue with the "%1" ID doesn\'t exist.', $queueId));
        }
        return $queue;
    }

    /**
     *  Delete SubscriberQueue data by given email
     *
     * @param string $email
     * @param array $actions
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteByEmail(string $email, array $actions = [])
    {
        $searchCriteriaBuilder = $this->searchCriteriaBuilder
            ->addFilter(DataInterface::EMAIL, $email, 'eq')
            ->addFilter(DataInterface::JOB_ID, true, 'null');

        if ($actions) {
            $searchCriteriaBuilder->addFilter(DataInterface::ACTION, $actions, 'in');
        }

        $filter = $searchCriteriaBuilder->create();

        $list = $this->getList($filter);

        foreach ($list->getItems() as $subscriber) {
            $this->delete($subscriber);
        }
    }

    /**
     * Delete SubscriberQueue data by given email
     *
     * @param String $email
     * @param array $actions
     * @return DataInterface
     */
    public function getByEmail(string $email, $actions = [])
    {
        $searchCriteriaBuilder = $this->searchCriteriaBuilder
            ->addFilter(DataInterface::EMAIL, $email, 'eq')
            ->addFilter(DataInterface::JOB_ID, true, 'null');

        if ($actions) {
            $searchCriteriaBuilder->addFilter(DataInterface::ACTION, $actions, 'in');
        }

        $filter = $searchCriteriaBuilder->create();

        return $this->getFirstResult($filter);
    }

    /**
     * Get first result from criteria
     *
     * @param  \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function getFirstResult(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        return $collection->getFirstItem();
    }

    /**
     * Load Subscriber data collection by given search criteria
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
     * Delete Subscriber Queue
     *
     * @param  DataInterface $queue
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(DataInterface $queue)
    {
        try {
            $this->resource->delete($queue);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Subscriber Queue by given Queue Identity
     *
     * @param  Int $queueId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($queueId)
    {
        return $this->delete($this->getById($queueId));
    }

    /**
     * Set customer to be notified for sale
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param Int $productId
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterface|null $config
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function notifySaleProduct(CustomerInterface $customer, $productId, ConfigInterface $config = null)
    {
        $this->_notifyProduct($customer, $productId, $config, DataInterface::ACTIONS['SAL']);
    }

    /**
     * Set customer to be notified for product back in stock
     *
     * @param  \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param  Int                                          $productId
     * @param  \Mailjet\Mailjet\Api\Data\ConfigInterface    $config
     * @return void
     */
    public function notifyStockStatus(CustomerInterface $customer, $productId, ConfigInterface $config = null)
    {
        $this->_notifyProduct($customer, $productId, $config, DataInterface::ACTIONS['STK']);
    }

    /**
     * Notify product
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param int $productId
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterface $config
     * @param string $action
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    private function _notifyProduct(CustomerInterface $customer, $productId, $config, $action)
    {
        if ($customer->getStoreId()) {
            if (empty($config)) {
                $config = $this->configRepository->getByStoreId($customer->getStoreId());
            }

            if ($config->getId()) {
                $subscriberQueue = $this->getByEmail($customer->getEmail(), [$action]);

                if ($subscriberQueue->getId()) {
                    $property = $subscriberQueue->getProperty();
                    if (!in_array($productId, $property['productIds'])) {
                        $property['productIds'][] = $productId;
                    }
                } else {
                    $subscriberQueue = $this->modelFactory->create();
                    $property['productIds'][] = $productId;
                }

                $subscriberQueue
                    ->setProperty($property)
                    ->setEmail($customer->getEmail())
                    ->setAction($action)
                    ->setName($customer->getFirstName() . ' ' . $customer->getLastName())
                    ->setConfigId($config->getId());

                $this->save($subscriberQueue);
            }
        }
    }

    /**
     * Update customer Ecommerce data
     *
     * @param  \Magento\Customer\Api\Data\CustomerInterface      $customer
     * @param  \Mailjet\Mailjet\Api\Data\ConfigInterface|null    $config
     * @return void
     */
    public function updateEcommerceData(CustomerInterface $customer, ConfigInterface $config = null)
    {
        if ($customer->getStoreId()) {
            if (empty($config)) {
                $config = $this->configRepository->getByStoreId($customer->getStoreId());
            }

            if ($config->getId() && $config->getEcommerceData()) {
                $this->deleteByEmail($customer->getEmail(), [DataInterface::ACTIONS['UPD']]);

                $subscriberQueue = $this->modelFactory->create();

                $data = $this->apiEcommerce->getEcommerceData($customer);

                $subscriberQueue
                    ->setProperty($data)
                    ->setEmail($customer->getEmail())
                    ->setAction(DataInterface::ACTIONS['UPD'])
                    ->setName('')
                    ->setConfigId($config->getId());

                $this->save($subscriberQueue);
            }
        }
    }

    /**
     * Set customer to be subscribed to Mailjet
     *
     * @param \Magento\Newsletter\Model\Subscriber|\Magento\Customer\Api\Data\CustomerInterface $subscriber
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterface|null $config
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function subscribe($subscriber, ConfigInterface $config = null)
    {
        $this->_subscriberSave($subscriber, $config, DataInterface::ACTIONS['SUB']);
    }

    /**
     * Set customer to be unsubscribed to Mailjet
     *
     * @param \Magento\Newsletter\Model\Subscriber|\Magento\Customer\Api\Data\CustomerInterface $subscriber
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterface|null $config
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function unsubscribe($subscriber, ConfigInterface $config = null)
    {
        $this->_subscriberSave($subscriber, $config, DataInterface::ACTIONS['UNS']);
    }

    /**
     * Set customer to be deleted from Mailjet
     *
     * @param \Magento\Newsletter\Model\Subscriber|\Magento\Customer\Api\Data\CustomerInterface $customer
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterface|null $config
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function deleteSubscription($customer, ConfigInterface $config = null)
    {
        if ($customer instanceof CustomerInterface) {
            $email = $customer->getEmail();
        } elseif ($customer instanceof \Magento\Newsletter\Model\Subscriber) {
            $email = $customer->getSubscriberEmail();
        }

        $storeId = $customer->getStoreId();

        if (!empty($storeId) && !empty($email)) {
            if (empty($config)) {
                $config = $this->configRepository->getByStoreId($storeId);
            }

            if ($config->getId()) {
                $this->deleteByEmail($email);

                $subscriberQueue = $this->modelFactory->create();

                $subscriberQueue
                    ->setProperty([])
                    ->setEmail($email)
                    ->setAction(DataInterface::ACTIONS['DEL'])
                    ->setName('')
                    ->setConfigId($config->getId());

                $this->save($subscriberQueue);
            }
        }
    }

    /**
     * Subscriber save
     *
     * @param Subscriber|CustomerInterface $subscriber
     * @param ConfigInterface $config
     * @param string $action
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function _subscriberSave($subscriber, $config, $action)
    {
        if ($subscriber->getStoreId()) {
            if (empty($config)) {
                $config = $this->configRepository->getByStoreId($subscriber->getStoreId());
            }

            if ($config->getId()) {
                if ($subscriber instanceof \Magento\Newsletter\Model\Subscriber) {
                    if ($subscriber->getCustomerId()) {
                        $customer = $this->customerRepository->getById($subscriber->getCustomerId());
                    }

                    $email = $subscriber->getSubscriberEmail();
                } elseif ($subscriber instanceof CustomerInterface) {
                    $customer = $subscriber;
                    $email = $customer->getEmail();
                }

                $this->deleteByEmail($email, [DataInterface::ACTIONS['UNS'], DataInterface::ACTIONS['SUB']]);

                $subscriberQueue = $this->modelFactory->create();

                $subscriberQueue
                    ->setEmail($email)
                    ->setAction($action)
                    ->setConfigId($config->getId());

                if (!empty($customer)) {
                    $name=$customer->getFirstname() . ' ' . $customer->getMiddlename() . ' ' . $customer->getLastname();
                    $subscriberQueue->setName($name);

                    if ($config->getEcommerceData()) {
                        $data = $this->apiEcommerce->getEcommerceData($customer);
                        $subscriberQueue->setProperty($data);
                    }
                }

                $this->save($subscriberQueue);
            }
        }
    }
}
