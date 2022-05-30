<?php

namespace Mailjet\Mailjet\Block\Adminhtml\Marketing;

use Magento\Framework\Exception\NoSuchEntityException;
use Mailjet\Mailjet\Helper\Iframe as HelperIframe;

class Iframe extends \Magento\Backend\Block\Template
{
    /**
     * @var \Mailjet\Mailjet\Model\Api\Iframe
     */
    private $apiIframe;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * Iframe constructor.
     *
     * @param \Mailjet\Mailjet\Model\Api\Iframe          $apiIframe
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Backend\Block\Template\Context    $context
     * @param array                                      $data
     */
    public function __construct(
        \Mailjet\Mailjet\Model\Api\Iframe $apiIframe,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->apiIframe = $apiIframe;
        $this->storeManager = $storeManager;

        parent::__construct($context, $data);
    }

    /**
     * Get Iframe
     *
     * @return String
     * @throws NoSuchEntityException
     */
    public function getIframe()
    {
        return $this->apiIframe->getIframeHTML($this->storeManager->getStore()->getId(), HelperIframe::URLS['stats']);
    }
}
