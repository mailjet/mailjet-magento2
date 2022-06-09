<?php

namespace Mailjet\Mailjet\Model\System\Config\Source;

class Port implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * To option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $ports = [];

        foreach (\Mailjet\Mailjet\Helper\Data::SMTP_PORTS as $port) {
            $ports[] = ['value' => $port, 'label' => $port];
        }

        return $ports;
    }
}
