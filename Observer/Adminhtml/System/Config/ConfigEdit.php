<?php

namespace Mailjet\Mailjet\Observer\Adminhtml\System\Config;

class ConfigEdit implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Mailjet\Mailjet\Model\Api\Connection
     */
    protected $apiConnection;

    /**
     *
     * @param \Mailjet\Mailjet\Model\Api\Connection $apiConnection
     */
    public function __construct(
        \Mailjet\Mailjet\Model\Api\Connection $apiConnection
    ) {
        $this->apiConnection = $apiConnection;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return Void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $request = $observer->getRequest();

        if ($request->getParam('section') == 'transactional' || $request->getParam('section') == 'automation') {
            $this->apiConnection->setupTemplates();
        }
    }
}
