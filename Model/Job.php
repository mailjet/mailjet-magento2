<?php

namespace Mailjet\Mailjet\Model;

class Job extends \Magento\Framework\Model\AbstractModel implements \Mailjet\Mailjet\Api\Data\JobInterface
{
    protected function _construct()
    {
        $this->_init(\Mailjet\Mailjet\Model\ResourceModel\Job::class);
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
     * Get job id
     *
     * @return Int
     */
    public function getJobId()
    {
        return $this->getData(self::JOB_ID);
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
     * Get created at
     *
     * @return String
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Get executed at
     *
     * @return String
     */
    public function getExecutedAt()
    {
        return $this->getData(self::EXECUTED_AT);
    }

    /**
     * Get error id
     *
     * @return Int
     */
    public function getErrorId()
    {
        return $this->getData(self::ERROR_ID);
    }

    /**
     * Set ID
     *
     * @param Int $id
     * @return \Mailjet\Mailjet\Api\Data\JobInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Set ID
     *
     * @param Int $jobId
     * @return \Mailjet\Mailjet\Api\Data\JobInterface
     */
    public function setJobId($jobId)
    {
        return $this->setData(self::JOB_ID, $jobId);
    }

    /**
     * Set action
     *
     * @param String $action
     * @return \Mailjet\Mailjet\Api\Data\JobInterface
     */
    public function setAction($action)
    {
        return $this->setData(self::ACTION, $action);
    }

    /**
     * Set config id
     *
     * @param Int $configId
     * @return \Mailjet\Mailjet\Api\Data\JobInterface
     */
    public function setConfigId($configId)
    {
        return $this->setData(self::CONFIG_ID, $configId);
    }

    /**
     * Set config id
     *
     * @param String $createdAt
     * @return \Mailjet\Mailjet\Api\Data\JobInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Set executed at
     *
     * @param String $executedAt
     * @return \Mailjet\Mailjet\Api\Data\JobInterface
     */
    public function setExecutedAt($executedAt)
    {
        return $this->setData(self::EXECUTED_AT, $executedAt);
    }

    /**
     * Set error id
     *
     * @param Int $errorId
     * @return \Mailjet\Mailjet\Api\Data\JobInterface
     */
    public function setErrorId($errorId)
    {
        return $this->setData(self::ERROR_ID, $errorId);
    }
}
