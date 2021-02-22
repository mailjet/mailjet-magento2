<?php

namespace Mailjet\Mailjet\Model;

class SubscriberQueue extends \Magento\Framework\Model\AbstractModel implements \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
{
    protected function _construct()
    {
        $this->_init(\Mailjet\Mailjet\Model\ResourceModel\SubscriberQueue::class);
    }

    /**
     * Get ID
     *
     * @return Int
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * Get subscriber id
     *
     * @return Int
     */
    public function getSubscriberId()
    {
        return $this->getData(self::SUBSCRIBER_ID);
    }

    /**
     * Get property
     *
     * @return array
     */
    public function getProperty()
    {
        return json_decode($this->getData(self::PROPERTY), true);
    }

    /**
     * Get email
     *
     * @return String
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * Get name
     *
     * @return String
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Get action
     *
     * @return String
     */
    public function getAction()
    {
        return $this->getData(self::ACTION);
    }

    /**
     * Get config id
     *
     * @return Int
     */
    public function getConfigId()
    {
        return $this->getData(self::CONFIG_ID);
    }

    /**
     * Get job id
     *
     * @return Int
     */
    public function getJobId()
    {
        return $this->getData(self::JOB_ID);
    }

    /**
     * Set ID
     *
     * @param Int $id
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Set subscriber id
     *
     * @param Int $subscriberId
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function setSubscriberId($subscriberId)
    {
        return $this->setData(self::SUBSCRIBER_ID, $subscriberId);
    }

    /**
     * Set property
     *
     * @param array $property
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function setProperty($property)
    {
        return $this->setData(self::PROPERTY, json_encode($property));
    }

    /**
     * Set email
     *
     * @param String $email
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * Set name
     *
     * @param String $name
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Set action
     *
     * @param String $action
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function setAction($action)
    {
        return $this->setData(self::ACTION, $action);
    }

    /**
     * Set config id
     *
     * @param String $configId
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function setConfigId($configId)
    {
        return $this->setData(self::CONFIG_ID, $configId);
    }

    /**
     * Set job id
     *
     * @param String $jobId
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function setJobId($jobId)
    {
        return $this->setData(self::JOB_ID, $jobId);
    }
}
