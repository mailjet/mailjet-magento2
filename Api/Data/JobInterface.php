<?php

namespace Mailjet\Mailjet\Api\Data;

interface JobInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID                 = 'job_id';
    const JOB_ID             = 'job_id';
    const ACTION             = 'action';
    const CONFIG_ID          = 'config_id';
    const CREATED_AT         = 'created_at';
    const EXECUTED_AT        = 'executed_at';
    const ERROR_ID           = 'error_id';

    /**
     * Get ID
     *
     * @return Int
     */
    public function getId();

    /**
     * Get job id
     *
     * @return Int
     */
    public function getJobId();

    /**
     * Get action
     *
     * @return String
     */
    public function getAction();

    /**
     * Get config id
     *
     * @return Int
     */
    public function getConfigId();

    /**
     * Get created at
     *
     * @return String
     */
    public function getCreatedAt();

    /**
     * Get executed at
     *
     * @return String
     */
    public function getExecutedAt();

    /**
     * Get error id
     *
     * @return Int
     */
    public function getErrorId();

    /**
     * Set ID
     *
     * @param Int $id
     * @return \Mailjet\Mailjet\Api\Data\JobInterface
     */
    public function setId($id);

    /**
     * Set ID
     *
     * @param Int $jobId
     * @return \Mailjet\Mailjet\Api\Data\JobInterface
     */
    public function setJobId($jobId);

    /**
     * Set action
     *
     * @param String $action
     * @return \Mailjet\Mailjet\Api\Data\JobInterface
     */
    public function setAction($action);

    /**
     * Set config id
     *
     * @param Int $configId
     * @return \Mailjet\Mailjet\Api\Data\JobInterface
     */
    public function setConfigId($configId);

    /**
     * Set config id
     *
     * @param String $createdAt
     * @return \Mailjet\Mailjet\Api\Data\JobInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Set executed at
     *
     * @param String $executedAt
     * @return \Mailjet\Mailjet\Api\Data\JobInterface
     */
    public function setExecutedAt($executedAt);

    /**
     * Set error id
     *
     * @param Int $errorId
     * @return \Mailjet\Mailjet\Api\Data\JobInterface
     */
    public function setErrorId($errorId);
}
