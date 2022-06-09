<?php

namespace Mailjet\Mailjet\Model;

class Error extends \Magento\Framework\Model\AbstractModel implements \Mailjet\Mailjet\Api\Data\ErrorInterface
{
    /**
     * Error construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Mailjet\Mailjet\Model\ResourceModel\Error::class);
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
     * Get error id
     *
     * @return Int
     */
    public function getErrorId()
    {
        return $this->getData(self::ERROR_ID);
    }

    /**
     * Get message
     *
     * @return String
     */
    public function getMessage()
    {
        return $this->getData(self::MESSAGE);
    }

    /**
     * Get api result
     *
     * @return String
     */
    public function getApiResult()
    {
        return $this->getData(self::API_RESULT);
    }

    /**
     * Get status
     *
     * @return Int
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set ID
     *
     * @param  Int $id
     * @return \Mailjet\Mailjet\Api\Data\ErrorInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Set error id
     *
     * @param  Int $errorId
     * @return \Mailjet\Mailjet\Api\Data\ErrorInterface
     */
    public function setErrorId($errorId)
    {
        return $this->setData(self::ERROR_ID, $errorId);
    }

    /**
     * Set message
     *
     * @param  Int $message
     * @return \Mailjet\Mailjet\Api\Data\ErrorInterface
     */
    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }

    /**
     * Set api result
     *
     * @param  Int $apiResult
     * @return \Mailjet\Mailjet\Api\Data\ErrorInterface
     */
    public function setApiResult($apiResult)
    {
        return $this->setData(self::API_RESULT, $apiResult);
    }

    /**
     * Set status
     *
     * @param  Int $status
     * @return \Mailjet\Mailjet\Api\Data\ErrorInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
}
