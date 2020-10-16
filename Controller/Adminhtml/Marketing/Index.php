<?php

namespace Mailjet\Mailjet\Controller\Adminhtml\Marketing;

class Index extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Marketing'));
        $this->_view->renderLayout();
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mailjet_Mailjet::marketing_grid');
    }
}
