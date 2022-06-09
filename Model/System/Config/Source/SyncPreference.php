<?php

namespace Mailjet\Mailjet\Model\System\Config\Source;

use Mailjet\Mailjet\Helper\Data;

class SyncPreference implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * To option array
     *
     * @return array[]
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => ''],
            ['value' => Data::SYNC_PREFERENCE_ALL, 'label' => __('Sync all existing and future subscribers')],
            ['value' => Data::SYNC_PREFERENCE_ONLY_FUTURE, 'label' => __('Sync only future subscribers')],
        ];
    }
}
