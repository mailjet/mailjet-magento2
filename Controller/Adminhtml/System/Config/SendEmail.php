<?php

namespace Mailjet\Mailjet\Controller\Adminhtml\System\Config;

use Magento\Store\Model\ScopeInterface;
use Mailjet\Mailjet\Helper\Data;

class SendEmail extends \Mailjet\Mailjet\Controller\Adminhtml\System\Config\AbstractAction
{
    /**
     * @var \Mailjet\Mailjet\Model\Framework\Mail\Transport
     */
    private $transport;

    /**
     * @var \Magento\Framework\MailMessageFactory
     */
    private $mailMessageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context              $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Mailjet\Mailjet\Model\Api\Connection            $apiConnection
     * @param \Mailjet\Mailjet\Helper\Data                     $dataHelper
     * @param \Mailjet\Mailjet\Model\Framework\Mail\Transport  $transport
     * @param \Magento\Framework\MailMessageFactory            $mailMessageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Mailjet\Mailjet\Model\Api\Connection $apiConnection,
        Data $dataHelper,
        \Mailjet\Mailjet\Model\Framework\Mail\Transport $transport,
        \Magento\Framework\Mail\MessageFactory $mailMessageFactory
    ) {
        $this->transport          = $transport;
        $this->mailMessageFactory = $mailMessageFactory;

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
        $sended = false;
        $message = '';

        if ($this->getRequest()->isPost()
            && $this->getRequest()->getParam('api_key')
            && $this->getRequest()->getParam('smtp_email')
            && $this->getRequest()->getParam('smtp_port')
            && $this->getRequest()->getParam('use_ssl_tls')
        ) {
            $apiKey = $this->getRequest()->getParam('api_key');
            $email = $this->getRequest()->getParam('smtp_email');
            $port = $this->getRequest()->getParam('smtp_port');

            if (trim($this->getRequest()->getParam('secret_key'), '*')) {
                $secretKey = $this->getRequest()->getParam('secret_key');
            } else {
                if ($this->getRequest()->getParam('store_id')) {
                    $secretKey = $this->dataHelper->getConfigValue(
                        Data::CONFIG_PATH_ACCOUNT_SECRET_KEY,
                        $this->getRequest()->getParam('store_id')
                    );
                } elseif ($this->getRequest()->getParam('website_id')) {
                    $secretKey = $this->dataHelper->getConfigValue(
                        Data::CONFIG_PATH_ACCOUNT_SECRET_KEY,
                        $this->getRequest()->getParam('website_id'),
                        ScopeInterface::SCOPE_WEBSITES
                    );
                } else {
                    $secretKey = $this->dataHelper->getConfigValue(Data::CONFIG_PATH_ACCOUNT_SECRET_KEY);
                }

                $secretKey = $this->apiConnection->getEncryptor()->decrypt($secretKey);
            }

            if ($this->getRequest()->getParam('smtp_port') != 465) {
                $ssl = 'tls';
            } else {
                $ssl = $this->getRequest()->getParam('use_ssl_tls');
            }

            $config['host'] = Data::SMTP_HOST;
            $config['port'] = $port;
            $config['ssl'] = $ssl;
            $config['username'] = $apiKey;
            $config['password'] = $secretKey;
            $config['auth_type'] = 'login';

            $text  = 'Your Mailjet configuration is ok!' . PHP_EOL;
            $text .= 'Encryption: ' . $ssl . PHP_EOL;
            $text .= 'Port: ' . $port . PHP_EOL;

            $message = $this->mailMessageFactory->create();

            $message->setBodyText($text);
            $message->setSubject('Your test email from Mailjet');
            $message->setFrom($this->dataHelper->getConfigValue('trans_email/ident_general/email'));

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message->addTo($email);
                $sended = $this->transport->sendSmtpMessage($message, $config);

                if ($sended) {
                    $message = __('Mail sent successfully');
                } else {
                    $message = __('Mail failed to send');
                }
            } else {
                $message = __('Please enter a valid email address');
            }
        } else {
            $message = __('Mail failed to send');
        }

        $result = ['result' => $sended, 'message' => $message];

        return $resultJsonFactory->setData($result);
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
