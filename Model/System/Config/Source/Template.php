<?php

namespace Mailjet\Mailjet\Model\System\Config\Source;

use Mailjet\Mailjet\Helper\MailjetAPI;

class Template implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Mailjet\Mailjet\Model\Api\Connection
     */
    private $apiConnection;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;
    /**
     * @var array
     */
    private $options = [];

    /**
     * MailjetList constructor.
     *
     * @param \Mailjet\Mailjet\Model\Api\Connection $apiConnection
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Mailjet\Mailjet\Model\Api\Connection   $apiConnection,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->apiConnection = $apiConnection;
        $this->request = $request;
    }

    /**
     * To option array
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $connection = $this->apiConnection->getConnection();
            $section = $this->request->getParam('section');

            for ($i = 0; $i < 5 * MailjetAPI::TEMPLATE_RESULT_LIMIT; $i += MailjetAPI::TEMPLATE_RESULT_LIMIT) {
                $result = $connection->getTemplates($i, MailjetAPI::TEMPLATE_PURPOSE[$section]);

                if ($result) {
                    foreach ($result as $template) {
                        $this->options[] = ['value' => $template[MailjetAPI::ID],
                            'label' => $template[MailjetAPI::NAME]];
                    }
                } else {
                    break;
                }
            }
        }

        return $this->options;
    }
}
