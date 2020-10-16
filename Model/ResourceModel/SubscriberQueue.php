<?php

namespace Mailjet\Mailjet\Model\ResourceModel;

class SubscriberQueue extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('mailjet_subscriber_queue', 'subscriber_id');
    }
}
