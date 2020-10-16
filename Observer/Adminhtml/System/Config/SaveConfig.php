<?php

namespace Mailjet\Mailjet\Observer\Adminhtml\System\Config;

class SaveConfig implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Mailjet\Mailjet\Api\ConfigRepositoryInterface
     */
    protected $configRepository;

    /**
     * @var \Mailjet\Mailjet\Model\Api\Connection
     */
    protected $apiConnection;

    /**
     * Generate configs constructor.
     *
     * @param \Mailjet\Mailjet\Api\ConfigRepositoryInterface $configRepository
     * @param \Mailjet\Mailjet\Model\Api\Connection $apiConnection
     */
    public function __construct(
        \Mailjet\Mailjet\Api\ConfigRepositoryInterface $configRepository,
        \Mailjet\Mailjet\Model\Api\Connection $apiConnection
    ) {
        $this->configRepository = $configRepository;
        $this->apiConnection    = $apiConnection;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return Void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->configRepository->generateConfigs();
        $this->apiConnection->setupEvents();
        $this->apiConnection->setupProperties();
        $this->apiConnection->setupSegments();
    }
}
