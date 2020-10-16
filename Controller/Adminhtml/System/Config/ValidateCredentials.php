<?php

namespace Mailjet\Mailjet\Controller\Adminhtml\System\Config;

class ValidateCredentials extends \Mailjet\Mailjet\Controller\Adminhtml\System\Config\AbstractAction
{
    /**
     * @var \Mailjet\Mailjet\Model\System\Config\Source\MailjetList
     */
    protected $mailjetList;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Mailjet\Mailjet\Model\Api\Connection $apiConnection
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     * @param \Mailjet\Mailjet\Model\System\Config\Source\MailjetList $mailjetList
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Mailjet\Mailjet\Model\Api\Connection $apiConnection,
        \Mailjet\Mailjet\Helper\Data $dataHelper,
        \Mailjet\Mailjet\Model\System\Config\Source\MailjetList $mailjetList
    ) {
        $this->mailjetList = $mailjetList;

        parent::__construct(
            $context,
            $resultJsonFactory,
            $apiConnection,
            $dataHelper
        );
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultJsonFactory = $this->resultJsonFactory->create();

        if ($this->getRequest()->isPost() && $this->getRequest()->getParam('api_key') && $this->getRequest()->getParam('secret_key')) {
            $apiKey = $this->getRequest()->getParam('api_key');

            if (trim($this->getRequest()->getParam('secret_key'), '*')) {
                $secretKey = $this->getRequest()->getParam('secret_key');
            } else {
                if ($this->getRequest()->getParam('store_id')) {
                    $secretKey = $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ACCOUNT_SECRET_KEY, $this->getRequest()->getParam('store_id'));
                } elseif ($this->getRequest()->getParam('website_id')) {
                    $secretKey = $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ACCOUNT_SECRET_KEY, $this->getRequest()->getParam('website_id'), \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITES);
                } else {
                    $secretKey = $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ACCOUNT_SECRET_KEY);
                }

                $secretKey = $this->apiConnection->getEncryptor()->decrypt($secretKey);
            }

            $connection = $this->apiConnection->getConnection(null, $apiKey, $secretKey);

            if ($connection->validateCredentials()) {
                $mailjetList = $this->mailjetList->getOptionArray($connection);

                $result = ['result' => true, 'mailjet_list' => $mailjetList];
            } else {
                $result = ['result' => false];
            }

            return $resultJsonFactory->setData($result);
        }
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
