<?php

namespace Mailjet\Mailjet\Controller\Adminhtml\System\Config;

use Magento\Store\Model\ScopeInterface;
use Mailjet\Mailjet\Helper\Data;

class Resync extends \Mailjet\Mailjet\Controller\Adminhtml\System\Config\AbstractAction
{
    /**
     * @var \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface
     */
    protected $subscriberQueueRepository;

    /**
     * @var \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory
     */
    protected $subscriberCollectionFactory;

    /**
     * @var \Mailjet\Mailjet\Api\ConfigRepositoryInterface
     */
    protected $configRepository;

    /**
     * @var \Mailjet\Mailjet\Api\Data\ConfigInterfaceFactory
     */
    protected $configFactory;

    /**
     * @var \Mailjet\Mailjet\Model\JobRepository
     */
    protected $jobRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \Magento\Backend\App\Action\Context                                  $context
     * @param \Magento\Framework\Controller\Result\JsonFactory                     $resultJsonFactory
     * @param \Mailjet\Mailjet\Model\Api\Connection                                $apiConnection
     * @param \Mailjet\Mailjet\Helper\Data                                         $dataHelper
     * @param \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface              $subscriberQueueRepository
     * @param \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory $subscriberCollectionFactory
     * @param \Mailjet\Mailjet\Api\ConfigRepositoryInterface                       $configRepository
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterfaceFactory                     $configFactory
     * @param \Mailjet\Mailjet\Model\JobRepository                                 $jobRepository
     * @param \Magento\Store\Model\StoreManagerInterface                           $storeManager
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Mailjet\Mailjet\Model\Api\Connection $apiConnection,
        Data $dataHelper,
        \Mailjet\Mailjet\Api\SubscriberQueueRepositoryInterface $subscriberQueueRepository,
        \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory $subscriberCollectionFactory,
        \Mailjet\Mailjet\Api\ConfigRepositoryInterface $configRepository,
        \Mailjet\Mailjet\Api\Data\ConfigInterfaceFactory $configFactory,
        \Mailjet\Mailjet\Model\JobRepository $jobRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->subscriberQueueRepository   = $subscriberQueueRepository;
        $this->subscriberCollectionFactory = $subscriberCollectionFactory;
        $this->configRepository            = $configRepository;
        $this->configFactory               = $configFactory;
        $this->jobRepository               = $jobRepository;
        $this->storeManager                = $storeManager;

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

        if ($this->getRequest()->isPost()
            && $this->getRequest()->getParam('api_key')
            && $this->getRequest()->getParam('secret_key')
            && $this->getRequest()->getParam('list')
            && $this->getRequest()->getParam('sync_preference')
            && $this->getRequest()->getParam('ecommerce_data')
        ) {
            $syncPreference = $this->getRequest()->getParam('sync_preference');
            $list = $this->getRequest()->getParam('list');
            $apiKey = $this->getRequest()->getParam('api_key');
            $ecommerceData = $this->getRequest()->getParam('ecommerce_data');

            if (trim($this->getRequest()->getParam('secret_key'), '*')) {
                $secretKey = $this->apiConnection->getEncryptor()->encrypt($this->getRequest()->getParam('secret_key'));
            } else {
                if ($this->getRequest()->getParam('website_id')) {
                    $secretKey = $this->dataHelper->getConfigValue(
                        Data::CONFIG_PATH_ACCOUNT_SECRET_KEY,
                        $this->getRequest()->getParam('website_id'),
                        ScopeInterface::SCOPE_WEBSITES
                    );
                } else {
                    $secretKey = $this->dataHelper->getConfigValue(Data::CONFIG_PATH_ACCOUNT_SECRET_KEY);
                }
            }

            $config = $this->configFactory->create();
            $config->setApiKey($apiKey)
                ->setSecretKey($secretKey)
                ->setList($list)
                ->setSyncPreference($syncPreference)
                ->setStoreId(0)
                ->setEcommerceData($ecommerceData)
                ->setDeleted(1);

            $this->configRepository->save($config);

            if ($config->getId()) {
                $website = $this->storeManager->getWebsite($this->getRequest()->getParam('website_id'));
                $collection = $this->subscriberCollectionFactory->create()->addStoreFilter($website->getStoreIds());

                foreach ($collection as $subscriber) {
                    if ($subscriber->getSubscriberStatus() == \Magento\Newsletter\Model\Subscriber::STATUS_SUBSCRIBED) {
                        $this->subscriberQueueRepository->subscribe($subscriber, $config);
                    } else {
                        $this->subscriberQueueRepository->unsubscribe($subscriber, $config);
                    }
                }

                $this->jobRepository->generateJobs([$config]);
            }

            $result = ['result' => true];
        } else {
            $result = ['result' => false, 'mailjet_error' => __('Credentials not valid or list not selected')];
        }

        return $resultJsonFactory->setData($result);
    }

    /**
     * Determines whether current user is allowed to access Action
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return parent::_isAllowed('Mailjet_Mailjet::resync');
    }
}
