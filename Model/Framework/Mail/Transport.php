<?php

namespace Mailjet\Mailjet\Model\Framework\Mail;

class Transport
{
    /**
     * @var \Mailjet\Mailjet\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * Transport model construct
     *
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     * @param \Magento\Framework\Escaper   $escaper
     */
    public function __construct(
        \Mailjet\Mailjet\Helper\Data $dataHelper,
        \Magento\Framework\Escaper $escaper
    ) {
        $this->dataHelper = $dataHelper;
        $this->escaper    = $escaper;
    }

    /**
     * Send smtp message
     *
     * @param \Magento\Framework\Mail\EmailMessageInterface|\Zend\Mail\Message $message
     * @param array $config
     * @return bool
     */
    public function sendSmtpMessage($message, $config)
    {
        if (!($message instanceof \Zend\Mail\Message)) {
            $message = \Zend\Mail\Message::fromString($message->getRawMessage());
        }

        //set config
        $options   = new \Zend\Mail\Transport\SmtpOptions(
            [
            // 'name' => '',
            'host' => $config['host'],
            'port' => $config['port'],
            ]
        );

        $options->setConnectionClass('login');

        $connectionConfig = [
            'username' => $config['username'],
            'password' => $config['password'],
            'ssl'      => $config['ssl'],
        ];

        $options->setConnectionConfig($connectionConfig);

        try {
            $transport = new \Zend\Mail\Transport\Smtp();
            $transport->setOptions($options);
            $transport->send($message);
            $toArr = [];

            foreach ($message->getTo() as $toAddr) {
                $toArr[] = $toAddr->getEmail();
            }

            $data['recipient'] = implode(',', $toArr);
            $data['sender'] = $message->getFrom()->rewind()->getEmail();
            $data['subject'] = $message->getSubject();
            $data['body'] = $this->escaper->escapeHtml($message->toString());

            return true;
        } catch (Exception $e) {
            throw new MailException(
                new Phrase($e->getMessage()),
                $e
            );
        }
    }
}
