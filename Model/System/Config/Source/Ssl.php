<?php

namespace Mailjet\Mailjet\Model\System\Config\Source;

class Ssl implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        $ssl = [];

        foreach (\Mailjet\Mailjet\Helper\Data::SMTP_SSL as $key => $value) {
            $ssl[] = ['value' => $key, 'label' => $value];
        }

        return $ssl;
    }
}
