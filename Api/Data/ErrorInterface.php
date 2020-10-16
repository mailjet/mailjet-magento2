<?php

namespace Mailjet\Mailjet\Api\Data;

interface ErrorInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID           = 'error_id';
    const ERROR_ID     = 'error_id';
    const MESSAGE      = 'message';
    const API_RESULT   = 'api_result';
    const STATUS       = 'status';

    /**
     * Get ID
     *
     * @return Int
     */
    public function getId();

    /**
     * Get error id
     *
     * @return Int
     */
    public function getErrorId();

    /**
     * Get message
     *
     * @return String
     */
    public function getMessage();

    /**
     * Get api result
     *
     * @return String
     */
    public function getApiResult();

    /**
     * Get status
     *
     * @return Int
     */
    public function getStatus();

    /**
     * Set ID
     *
     * @param Int $id
     * @return \Mailjet\Mailjet\Api\Data\ErrorInterface
     */
    public function setId($id);

    /**
     * Set error id
     *
     * @param Int $errorId
     * @return \Mailjet\Mailjet\Api\Data\ErrorInterface
     */
    public function setErrorId($errorId);

    /**
     * Set message
     *
     * @param Int $message
     * @return \Mailjet\Mailjet\Api\Data\ErrorInterface
     */
    public function setMessage($message);

    /**
     * Set api result
     *
     * @param Int $apiResult
     * @return \Mailjet\Mailjet\Api\Data\ErrorInterface
     */
    public function setApiResult($apiResult);

    /**
     * Set status
     *
     * @param Int $status
     * @return \Mailjet\Mailjet\Api\Data\ErrorInterface
     */
    public function setStatus($status);
}
