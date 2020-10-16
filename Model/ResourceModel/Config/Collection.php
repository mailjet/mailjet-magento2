<?php

namespace Mailjet\Mailjet\Model\ResourceModel\Config;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init(
            \Mailjet\Mailjet\Model\Config::class,
            \Mailjet\Mailjet\Model\ResourceModel\Config::class
        );
    }

    public function addGroups($groups)
    {
        foreach ($groups as $group) {
            $this->getSelect()->group($group);
        }

        return $this;
    }
}
