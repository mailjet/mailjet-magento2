<?php

namespace Mailjet\Mailjet\Api\Data;

interface SubscriberQueueInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID             = 'subscriber_id';
    const SUBSCRIBER_ID  = 'subscriber_id';
    const PROPERTY       = 'property';
    const EMAIL          = 'email';
    const NAME           = 'name';
    const ACTION         = 'action';
    const CONFIG_ID      = 'config_id';
    const JOB_ID         = 'job_id';

    /**
     * Action constant
     * Customer Subscribed | Customer Unsubscribed | Customer Deleted | Customer Update profile | Product back in stock notify customer | Product on sale
     * SUB | UNS | DEL | UPD | STK | SAL
     */
    const ACTIONS = [
        'SUB' => 'SUB',
        'UNS' => 'UNS',
        'DEL' => 'DEL',
        'UPD' => 'UPD',
        'STK' => 'STK',
        'SAL' => 'SAL',
    ];

    /**
     * Get ID
     *
     * @return Int
     */
    public function getId();

    /**
     * Get subscriber id
     *
     * @return Int
     */
    public function getSubscriberId();

    /**
     * Get property
     *
     * @return String
     */
    public function getProperty();

    /**
     * Get email
     *
     * @return String
     */
    public function getEmail();

    /**
     * Get name
     *
     * @return String
     */
    public function getName();

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
     * Get job id
     *
     * @return Int
     */
    public function getJobId();

    /**
     * Set ID
     *
     * @param Int $id
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function setId($id);

    /**
     * Set subscriber id
     *
     * @param Int $subscriberId
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function setSubscriberId($subscriberId);

    /**
     * Set property
     *
     * @param Int $property
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function setProperty($property);

    /**
     * Set email
     *
     * @param String $email
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function setEmail($email);

    /**
     * Set name
     *
     * @param String $name
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function setName($name);

    /**
     * Set action
     *
     * @param String $action
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function setAction($action);

    /**
     * Set config id
     *
     * @param String $configId
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function setConfigId($configId);

    /**
     * Set job id
     *
     * @param String $jobId
     * @return \Mailjet\Mailjet\Api\Data\SubscriberQueueInterface
     */
    public function setJobId($jobId);
}
