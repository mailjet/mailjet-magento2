<?php

namespace Mailjet\Mailjet\Api;

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
    public function getById($queueId);

    /**
     * Delete SubscriberQueue data by given email
     *
     * @param String $email
     * @return Void
     */
    public function deleteByEmail($email, $actions = []);

    /**
     * Delete SubscriberQueue data by given email
     *
     * @param String $email
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function getByEmail($email, $actions = []);

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
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param Int $productId
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterface $config
     * @return void
     */
    public function notifySaleProduct(\Magento\Customer\Api\Data\CustomerInterface $customer, $productId, \Mailjet\Mailjet\Api\Data\ConfigInterface $config = null);

    /**
     * Set customer to be notified for product back in stock
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param Int $productId
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterface $config
     * @return void
     */
    public function notifyStockStatus(\Magento\Customer\Api\Data\CustomerInterface $customer, $productId, \Mailjet\Mailjet\Api\Data\ConfigInterface $config = null);

    /**
     * Update customer Ecommerce data
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterface $config
     * @return void
     */
    public function updateEcommerceData(\Magento\Customer\Api\Data\CustomerInterface $customer, \Mailjet\Mailjet\Api\Data\ConfigInterface $config = null);

    /**
     * Set customer to be subscribed to Mailjet
     *
     * @param \Magento\Newsletter\Model\Subscriber | \Magento\Customer\Api\Data\CustomerInterface $subscriber
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterface $config
     * @return void
     */
    public function subscribe($subscriber, \Mailjet\Mailjet\Api\Data\ConfigInterface $config = null);

    /**
     * Set customer to be unsubscribed to Mailjet
     *
     * @param \Magento\Newsletter\Model\Subscriber | \Magento\Customer\Api\Data\CustomerInterface $subscriber
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterface $config
     * @return void
     */
    public function unsubscribe($subscriber, \Mailjet\Mailjet\Api\Data\ConfigInterface $config = null);

    /**
     * Set customer to be deleted from Mailjet
     *
     * @param \Magento\Newsletter\Model\Subscriber | \Magento\Customer\Api\Data\CustomerInterface $subscriber
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterface $config
     * @return void
     */
    public function deleteSubscription($customer, \Mailjet\Mailjet\Api\Data\ConfigInterface $config = null);
}
