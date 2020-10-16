<?php

namespace Mailjet\Mailjet\Model\System\Config\Source;

class SyncPreference implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => ''],
            ['value' => \Mailjet\Mailjet\Helper\Data::SYNC_PREFERENCE_ALL, 'label' => __('Sync all existing and future subscribers')],
            ['value' => \Mailjet\Mailjet\Helper\Data::SYNC_PREFERENCE_ONLY_FUTURE, 'label' => __('Sync only future subscribers')],
        ];
    }
}
