<?php

namespace Mailjet\Mailjet\Controller\Adminhtml\System\Config;

abstract class AbstractAction extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Mailjet\Mailjet\Model\Api\Connection
     */
    protected $apiConnection;

    /**
     * @var \Mailjet\Mailjet\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Mailjet\Mailjet\Model\Api\Connection $apiConnection
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Mailjet\Mailjet\Model\Api\Connection $apiConnection,
        \Mailjet\Mailjet\Helper\Data $dataHelper
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->apiConnection     = $apiConnection;
        $this->dataHelper        = $dataHelper;

        parent::__construct($context);
    }
}
