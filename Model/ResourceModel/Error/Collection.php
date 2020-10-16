<?php

namespace Mailjet\Mailjet\Model\ResourceModel\Error;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init(
            \Mailjet\Mailjet\Model\Error::class,
            \Mailjet\Mailjet\Model\ResourceModel\Error::class
        );
    }
}
