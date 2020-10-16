<?php

namespace Mailjet\Mailjet\Model\ResourceModel;

class Job extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('mailjet_job', 'job_id');
    }
}
