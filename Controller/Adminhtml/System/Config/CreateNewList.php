<?php

namespace Mailjet\Mailjet\Controller\Adminhtml\System\Config;

class CreateNewList extends \Mailjet\Mailjet\Controller\Adminhtml\System\Config\AbstractAction
{
    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultJsonFactory = $this->resultJsonFactory->create();

        if ($this->getRequest()->isPost() && $this->getRequest()->getParam('new_list_name')) {
            $connection = $this->apiConnection->getConnection();
            $contactList = $connection->createContactList($this->getRequest()->getParam('new_list_name'));

            if ($contactList) {
                $result = ['result' => true, 'contactList' => $contactList];
            } else {
                $result = ['result' => false, 'mailjet_error' => $connection->getError()];
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
        return parent::_isAllowed('Mailjet_Mailjet::create_new_list');
    }
}
