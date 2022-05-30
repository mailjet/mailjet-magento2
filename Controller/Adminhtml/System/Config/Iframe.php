<?php

namespace Mailjet\Mailjet\Controller\Adminhtml\System\Config;

use Mailjet\Mailjet\Helper\Iframe as HelperIframe;

class Iframe extends \Mailjet\Mailjet\Controller\Adminhtml\System\Config\AbstractAction
{
    /**
     * @var \Mailjet\Mailjet\Model\Api\Iframe
     */
    protected $apiIframe;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \Magento\Backend\App\Action\Context              $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Mailjet\Mailjet\Model\Api\Connection            $apiConnection
     * @param \Mailjet\Mailjet\Helper\Data                     $dataHelper
     * @param \Mailjet\Mailjet\Model\Api\Iframe                $apiIframe
     * @param \Magento\Store\Model\StoreManagerInterface       $storeManager
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Mailjet\Mailjet\Model\Api\Connection $apiConnection,
        \Mailjet\Mailjet\Helper\Data $dataHelper,
        \Mailjet\Mailjet\Model\Api\Iframe $apiIframe,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->apiIframe    = $apiIframe;
        $this->storeManager = $storeManager;

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
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $resultJsonFactory = $this->resultJsonFactory->create();

        if ($this->getRequest()->isPost() && $this->getRequest()->getParam('template_id')) {
            if ($this->getRequest()->getParam('store_id')) {
                $storeId = $this->getRequest()->getParam('store_id');
            } elseif ($this->getRequest()->getParam('website_id')) {
                $storeId = $this->storeManager->getWebsite($this->getRequest()->getParam('website_id'))
                    ->getDefaultGroup()->getDefaultStoreId();
            } else {
                $storeId = $this->storeManager->getDefaultStoreView()->getId();
            }

            $iframe = $this->apiIframe
                ->getIframeHTML($storeId, HelperIframe::URLS['template'], $this->getRequest()->getParam('template_id'));

            if ($iframe) {
                $result = ['result' => true, 'iframe' => $iframe];
            } else {
                $result = ['result' => false];
            }

            return $resultJsonFactory->setData($result);
        }
    }

    /**
     * Determines whether current user is allowed to access Action
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
