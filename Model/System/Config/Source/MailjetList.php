<?php

namespace Mailjet\Mailjet\Model\System\Config\Source;

use Mailjet\Mailjet\Helper\MailjetAPI as MailjetAPI;

class MailjetList implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Mailjet\Mailjet\Model\Api\Connection
     */
    private $apiConnection;

    /**
     * MailjetList constructor
     *
     * @param \Mailjet\Mailjet\Model\Api\Connection $apiConnection
     */
    public function __construct(
        \Mailjet\Mailjet\Model\Api\Connection $apiConnection
    ) {
        $this->apiConnection = $apiConnection;
    }

    /**
     * To OptionArray
     *
     * @return array|array[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function toOptionArray()
    {
        return $this->getOptionArray($this->apiConnection->getConnection());
    }

    /**
     * Get option array
     *
     * @param \Mailjet\Mailjet\Helper\MailjetAPI $connection
     * @return array[]
     */
    public function getOptionArray(MailjetAPI $connection)
    {
        $contacts = [['value' => 0, 'label' => __('Create a new list')]];

        foreach ($connection->getAllContactLists() as $contact) {
            if (!$contact[MailjetAPI::IS_DELETED]) {
                $contacts[] = ['value' => $contact[MailjetAPI::ID],
                    'label' => __(
                        '%1 (%2 contacts)',
                        $contact[MailjetAPI::NAME],
                        $contact[MailjetAPI::SUBSCRIBER_COUNT]
                    )];
            }
        }

        return $contacts;
    }
}
