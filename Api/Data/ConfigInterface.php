<?php

namespace Mailjet\Mailjet\Api\Data;

interface ConfigInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID                  = 'config_id';
    const CONFIG_ID           = 'config_id';
    const API_KEY             = 'api_key';
    const SECRET_KEY          = 'secret_key';
    const LIST                = 'list';
    const SYNC_PREFERENCE     = 'sync_preference';
    const STORE_ID            = 'store_id';
    const UNSUBSCRIBE_EVENT   = 'unsubscribe_event';
    const ECOMMERCE_DATA      = 'ecommerce_data';
    const ENABLED             = 'enabled';
    const IFRAME_TOKEN        = 'iframe_token';
    const IFRAME_TOKEN_EXPIRE = 'iframe_token_expire';
    const DELETED             = 'deleted';
    const HAS_ERRORS          = 'has_errors';

    /**
     * Get ID
     *
     * @return Int
     */
    public function getId();

    /**
     * Get config id
     *
     * @return Int
     */
    public function getConfigId();

    /**
     * Get api key
     *
     * @return String
     */
    public function getApiKey();

    /**
     * Get secret key
     *
     * @return String
     */
    public function getSecretKey();

    /**
     * Get list
     *
     * @return String
     */
    public function getList();

    /**
     * Get sync preference
     *
     * @return Int
     */
    public function getSyncPreference();

    /**
     * Get store id
     *
     * @return Int
     */
    public function getStoreId();

    /**
     * Get unsubscribe event
     *
     * @return Int
     */
    public function getUnsubscribeEvent();

    /**
     * Get ecommerce data
     *
     * @return Int
     */
    public function getEcommerceData();

    /**
     * Get enabled
     *
     * @return Int
     */
    public function getEnabled();

    /**
     * Get iframe token
     *
     * @return String
     */
    public function getIframeToken();

    /**
     * Get iframe token expire
     *
     * @return String
     */
    public function getIframeTokenExpire();

    /**
     * Get deleted
     *
     * @return Int
     */
    public function getDeleted();

    /**
     * Get has errors
     *
     * @return Int
     */
    public function getHasErrors();

    /**
     * Set ID
     *
     * @param Int $id
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setId($id);

    /**
     * Set ID
     *
     * @param Int $configId
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setConfigId($configId);

    /**
     * Set api key
     *
     * @param String $apiKey
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setApiKey($apiKey);

    /**
     * Set secret key
     *
     * @param String $secretKey
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setSecretKey($secretKey);

    /**
     * Set list
     *
     * @param String $list
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setList($list);

    /**
     * Set sync preference
     *
     * @param Int $syncPreference
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setSyncPreference($syncPreference);

    /**
     * Set store id
     *
     * @param Int $storeId
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setStoreId($storeId);

    /**
     * Set unsubscribe event
     *
     * @return Int
     */
    public function setUnsubscribeEvent($event);

    /**
     * Set ecommerce data
     *
     * @return Int
     */
    public function setEcommerceData($ecommerceData);

    /**
     * Set enabled
     *
     * @return Int
     */
    public function setEnabled($enabled);

    /**
     * Get iframe token
     *
     * @return String
     */
    public function setIframeToken($iframeToken);

    /**
     * Get iframe token expire
     *
     * @return String
     */
    public function setIframeTokenExpire($iframeTokenExpire);

    /**
     * Set deleted
     *
     * @param Int $deleted
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setDeleted($deleted);

    /**
     * Set has errors
     *
     * @param Int $hasErrors
     * @return \Mailjet\Mailjet\Api\Data\ConfigInterface
     */
    public function setHasErrors($hasErrors);
}
