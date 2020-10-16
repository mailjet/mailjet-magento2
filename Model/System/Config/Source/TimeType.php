<?php

namespace Mailjet\Mailjet\Model\System\Config\Source;

class TimeType implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'minutes', 'label' => __('Minutes')],
            ['value' => 'hours', 'label' => __('Hours')],
        ];
    }
}
