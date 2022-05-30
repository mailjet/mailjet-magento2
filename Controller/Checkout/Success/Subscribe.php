<?php

namespace Mailjet\Mailjet\Controller\Checkout\Success;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Newsletter\Model\Subscriber;
use Magento\Newsletter\Model\SubscriberFactory;
use Mailjet\Mailjet\Helper\Data;

class Subscribe extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Mailjet\Mailjet\Helper\Data
     */
    private $dataHelper;

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    private $subscriberFactory;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $resultJsonFactory;

    /**
     * Subscribe constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context           $context,
        Data              $dataHelper,
        SubscriberFactory $subscriberFactory,
        JsonFactory       $resultJsonFactory
    ) {
        $this->dataHelper = $dataHelper;
        $this->subscriberFactory = $subscriberFactory;
        $this->resultJsonFactory = $resultJsonFactory;

        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultJsonFactory = $this->resultJsonFactory->create();

        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $email = (string)$this->getRequest()->getPost('email');

            $subscriber = $this->subscriberFactory->create()->loadByEmail($email);
            if (!($subscriber->getId() && (int)$subscriber->getSubscriberStatus() === Subscriber::STATUS_SUBSCRIBED)) {
                $status = (int)$this->subscriberFactory->create()->subscribe($email);
                $result = ['result' => (bool)$status,
                    'text' => $this->dataHelper->getConfigValue(Data::CONFIG_PATH_ECOMMERCE_SUCCSESS_MESSAGE)];
            } else {
                $result = ['result' => false, 'text' => __('1% is awready subscribed', $email)];
            }
        } else {
            $result = ['result' => false, 'text' => __('email missing')];
        }

        return $resultJsonFactory->setData($result);
    }
}
