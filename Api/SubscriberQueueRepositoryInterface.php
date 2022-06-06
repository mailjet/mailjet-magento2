<?php

namespace Mailjet\Mailjet\Api;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Newsletter\Model\Subscriber;
use Mailjet\Mailjet\Api\Data\ConfigInterface;

interface SubscriberQueueRepositoryInterface
{
    /**
     * Save queue.
     *
     * @param \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface $queue
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Mailjet\Mailjet\Api\Data\SubscriberQueueInterface $queue);

    /**
     * Retrieve queue.
     *
     * @param int $queueId
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById(int $queueId);

    /**
     * Delete SubscriberQueue data by given email
     *
     * @param string $email
     * @param array $actions
     * @return void
     */
    public function deleteByEmail(string $email, array $actions = []);

    /**
     * Delete SubscriberQueue data by given email
     *
     * @param string $email
     * @param array $actions
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function getByEmail(string $email, $actions = []);

    /**
     * Get first result from criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function getFirstResult(\Magento\Framework\Api\SearchCriteriaInterface $criteria);

    /**
     * Retrieve queues matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete queue.
     *
     * @param \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface $queue
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Mailjet\Mailjet\Api\Data\SubscriberQueueInterface $queue);

    /**
     * Delete queue by ID.
     *
     * @param int $queueId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($queueId);

    /**
     * Set customer to be notified for sale
     *
     * @param CustomerInterface $customer
     * @param Int $productId
     * @param ConfigInterface|null $config
     * @return void
     */
    public function notifySaleProduct(CustomerInterface $customer, int $productId, ConfigInterface $config = null);

    /**
     * Set customer to be notified for product back in stock
     *
     * @param CustomerInterface $customer
     * @param Int $productId
     * @param ConfigInterface|null $config
     * @return void
     */
    public function notifyStockStatus(CustomerInterface $customer, int $productId, ConfigInterface $config = null);

    /**
     * Update customer Ecommerce data
     *
     * @param CustomerInterface $customer
     * @param ConfigInterface|null $config
     * @return void
     */
    public function updateEcommerceData(CustomerInterface $customer, ConfigInterface $config = null);

    /**
     * Set customer to be subscribed to Mailjet
     *
     * @param CustomerInterface|Subscriber $subscriber
     * @param ConfigInterface|null $config
     * @return void
     */
    public function subscribe($subscriber, ConfigInterface $config = null);

    /**
     * Set customer to be unsubscribed to Mailjet
     *
     * @param CustomerInterface|Subscriber $subscriber
     * @param ConfigInterface|null $config
     * @return void
     */
    public function unsubscribe($subscriber, ConfigInterface $config = null);

    /**
     * Set customer to be deleted from Mailjet
     *
     * @param Subscriber|CustomerInterface $customer
     * @param ConfigInterface|null $config
     * @return void
     */
    public function deleteSubscription($customer, ConfigInterface $config = null);
}
