<?php

namespace Mailjet\Mailjet\Model\ResourceModel\SubscriberQueue;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init(
            \Mailjet\Mailjet\Model\SubscriberQueue::class,
            \Mailjet\Mailjet\Model\ResourceModel\SubscriberQueue::class
        );
    }
}
