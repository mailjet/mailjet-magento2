<?php

namespace Mailjet\Mailjet\Model\Api;

use Mailjet\Mailjet\Api\Data\ConfigInterface;
use Mailjet\Mailjet\Helper\MailjetAPI as MailjetAPI;
use Mailjet\Mailjet\Helper\Iframe as IframeHelper;

class Iframe
{
    /**
     * @var \Mailjet\Mailjet\Helper\IframeFactory
     */
    protected $iframeHelperFactory;

    /**
     * @var \Mailjet\Mailjet\Model\Api\Connection
     */
    protected $apiConnection;

    /**
     * @var \Mailjet\Mailjet\Api\ConfigRepositoryInterface
     */
    protected $configRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * Iframe constructor
     *
     * @param \Mailjet\Mailjet\Helper\IframeFactory          $iframeHelperFactory
     * @param \Mailjet\Mailjet\Model\Api\Connection          $apiConnection
     * @param \Mailjet\Mailjet\Api\ConfigRepositoryInterface $configRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder   $searchCriteriaBuilder
     * @param \Magento\Framework\Stdlib\DateTime\DateTime    $dateTime
     */
    public function __construct(
        \Mailjet\Mailjet\Helper\IframeFactory $iframeHelperFactory,
        \Mailjet\Mailjet\Model\Api\Connection $apiConnection,
        \Mailjet\Mailjet\Api\ConfigRepositoryInterface $configRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
    ) {
        $this->iframeHelperFactory   = $iframeHelperFactory;
        $this->apiConnection         = $apiConnection;
        $this->configRepository      = $configRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->dateTime              = $dateTime;
    }

    /**
     * Get iframe HTML
     *
     * @param  Int    $storeId
     * @param  String $page
     * @param  Int    $id
     * @return String
     */
    public function getIframeHTML($storeId, $page = null, $id = null)
    {
        $iframeConfig = $this->configRepository->getByStoreId($storeId);

        if ($iframeConfig->getIframeToken() && $iframeConfig->getIframeTokenExpire()
            && $this->dateTime->gmtTimestamp($iframeConfig->getIframeTokenExpire()) > $this->dateTime->gmtTimestamp()) {
            $iframeToken = $iframeConfig->getIframeToken();
        } else {
            $apiToken = $this->apiConnection->getConnection($iframeConfig)
                ->getApiToken(IframeHelper::PAGES, IframeHelper::SESSION_EXPIRATION);

            if (!empty($apiToken[0][MailjetAPI::TOKEN])) {
                $iframeToken = $apiToken[0][MailjetAPI::TOKEN];
            } else {
                return '';
            }

            $filter = $this->searchCriteriaBuilder
                ->addFilter(ConfigInterface::API_KEY, $iframeConfig->getApiKey(), 'eq')
                ->addFilter(ConfigInterface::SECRET_KEY, $iframeConfig->getSecretKey(), 'eq')
                ->addFilter(ConfigInterface::DELETED, true, 'null')
                ->create();

            $configs = $this->configRepository->getList($filter);

            foreach ($configs->getItems() as $config) {
                $timestamp = $this->dateTime->gmtTimestamp();
                $gmtdate = $this->dateTime->gmtDate(null, $timestamp + IframeHelper::SESSION_EXPIRATION);

                $config->setIframeToken($iframeToken)->setIframeTokenExpire($gmtdate);
                $this->configRepository->save($config);
            }
        }

        $iframeHelper = $this->iframeHelperFactory->create();

        if ($page) {
            $iframeHelper->setInitialPage($page);
        }

        if ($id) {
            $iframeHelper->setId($id);
        }

        return $iframeHelper->setTokenAccess($iframeToken)->getHtml();
    }
}
