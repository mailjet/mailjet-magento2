<?php

namespace Mailjet\Mailjet\Model\System\Config\Backend;

class TestSmtp extends \Magento\Framework\App\Config\Value
{
    /**
     * @var \Mailjet\Mailjet\Helper\Data
     */
    private $dataHelper;

    /**
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Mailjet\Mailjet\Helper\Data $dataHelper,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->dataHelper = $dataHelper;

        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Validate connection before save
     *
     * @return void
     */
    public function beforeSave()
    {
        if ($this->getValue()) {
            $data = $this->getFieldsetData();
            $hasAvailablePort = false;
            $configPort = $data['smtp_port'];
            $configSsl = !empty($data['use_ssl_tls']) ? $data['use_ssl_tls'] : 'tls';

            if (!$this->dataHelper->testSmtpConnection($configPort, $configSsl)) {
                foreach (\Mailjet\Mailjet\Helper\Data::SMTP_PORTS as $port) {
                    if ($this->dataHelper->testSmtpConnection($port, 'tls')) {
                        $hasAvailablePort = true;
                        throw new \Magento\Framework\Exception\LocalizedException(__('Warding: Port %1 with %2 is not available. Available %3 with TLS', $configPort, $configSsl, $port));
                    }
                }

                if (!$hasAvailablePort) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('Warding: Connection to Mailjet\'s SMTP couldn\'t be established. Please make sure your hosting is not blocking the SMTP ports and try again.'));
                }
            }
        }
    }
}
