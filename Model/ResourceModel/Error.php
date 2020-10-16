<?php

namespace Mailjet\Mailjet\Model\ResourceModel;

class Error extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('mailjet_error', 'error_id');
    }
}
