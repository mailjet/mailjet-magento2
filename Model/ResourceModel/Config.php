<?php

namespace Mailjet\Mailjet\Model\ResourceModel;

class Config extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('mailjet_config', 'config_id');
    }
}
