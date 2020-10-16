<?php

namespace Mailjet\Mailjet\Model\ResourceModel\Job;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init(
            \Mailjet\Mailjet\Model\Job::class,
            \Mailjet\Mailjet\Model\ResourceModel\Job::class
        );
    }
}
