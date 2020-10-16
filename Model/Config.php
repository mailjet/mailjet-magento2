<?php

namespace Mailjet\Mailjet\Model;

class Config extends \Magento\Framework\Model\AbstractModel implements \Mailjet\Mailjet\Api\Data\ConfigInterface
{
    protected function _construct()
    {
        $this->_init(\Mailjet\Mailjet\Model\ResourceModel\Config::class);
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
     * Get config id
     *
     * @return Int
     */
    public function getConfigId()
    {
        return $this->getData(self::CONFIG_ID);
    }

    /**
     * Get api key
     *
     * @return String
     */
    public function getApiKey()
    {
        return $this->getData(self::API_KEY);
    }

    /**
     * Get secret key
     *
     * @return String
     */
    public function getSecretKey()
    {
        return $this->getData(self::SECRET_KEY);
    }

    /**
     * Get list
     *
     * @return String
     */
    public function getList()
    {
        return $this->getData(self::LIST);
    }

    /**
     * Get sync preference
     *
     * @return Int
     */
    public function getSyncPreference()
    {
        return $this->getData(self::SYNC_PREFERENCE);
    }

    /**
     * Get store id
     *
     * @return Int
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * Get unsubscribe event
     *
     * @return Int
     */
    public function getUnsubscribeEvent()
    {
        return $this->getData(self::UNSUBSCRIBE_EVENT);
    }

    /**
     * Get ecommerce data
     *
     * @return Int
     */
    public function getEcommerceData()
    {
        return $this->getData(self::ECOMMERCE_DATA);
    }

    /**
     * Get enabled
     *
     * @return Int
     */
    public function getEnabled()
    {
        return $this->getData(self::ENABLED);
    }

    /**
     * Get iframe token
     *
     * @return String
     */
    public function getIframeToken()
    {
        return $this->getData(self::IFRAME_TOKEN);
    }

    /**
     * Get iframe token expire
     *
     * @return String
     */
    public function getIframeTokenExpire()
    {
        return $this->getData(self::IFRAME_TOKEN_EXPIRE);
    }

    /**
     * Get deleted
     *
     * @return Int
     */
    public function getDeleted()
    {
        return $this->getData(self::DELETED);
    }

    /**
     * Get has errors
     *
     * @return Int
     */
    public function getHasErrors()
    {
        return $this->getData(self::HAS_ERRORS);
    }

    /**
     * Set ID
     *
     * @param Int $id
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Set ID
     *
     * @param Int $configId
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setConfigId($configId)
    {
        return $this->setData(self::CONFIG_ID, $configId);
    }

    /**
     * Set api key
     *
     * @param String $apiKey
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setApiKey($apiKey)
    {
        return $this->setData(self::API_KEY, $apiKey);
    }

    /**
     * Set secret key
     *
     * @param String $secretKey
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setSecretKey($secretKey)
    {
        return $this->setData(self::SECRET_KEY, $secretKey);
    }

    /**
     * Set list
     *
     * @param String $list
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setList($list)
    {
        return $this->setData(self::LIST, $list);
    }

    /**
     * Set sync preference
     *
     * @param Int $syncPreference
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setSyncPreference($syncPreference)
    {
        return $this->setData(self::SYNC_PREFERENCE, $syncPreference);
    }

    /**
     * Set store id
     *
     * @param Int $storeId
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * Set unsubscribe event
     *
     * @return Int
     */
    public function setUnsubscribeEvent($event)
    {
        return $this->setData(self::UNSUBSCRIBE_EVENT, $event);
    }

    /**
     * Set ecommerce data
     *
     * @return Int
     */
    public function setEcommerceData($ecommerceData)
    {
        return $this->setData(self::ECOMMERCE_DATA, $ecommerceData);
    }

    /**
     * Set enabled
     *
     * @return Int
     */
    public function setEnabled($enabled)
    {
        return $this->setData(self::ENABLED, $enabled);
    }

    /**
     * Get iframe token
     *
     * @return String
     */
    public function setIframeToken($iframeToken)
    {
        return $this->setData(self::IFRAME_TOKEN, $iframeToken);
    }

    /**
     * Get iframe token expire
     *
     * @return String
     */
    public function setIframeTokenExpire($iframeTokenExpire)
    {
        return $this->setData(self::IFRAME_TOKEN_EXPIRE, $iframeTokenExpire);
    }

    /**
     * Set deleted
     *
     * @param Int $deleted
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setDeleted($deleted)
    {
        return $this->setData(self::DELETED, $deleted);
    }

    /**
     * Set has errors
     *
     * @param Int $hasErrors
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setHasErrors($hasErrors)
    {
        return $this->setData(self::HAS_ERRORS, $hasErrors);
    }
}
