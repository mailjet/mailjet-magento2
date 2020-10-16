<?php

namespace Mailjet\Mailjet\Block\Adminhtml\System\Config\Fieldset;

class ValidateCredentials extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var string
     */
    protected $_template = 'Mailjet_Mailjet::system/config/fieldset/validatecredentials.phtml';

    /**
     * Return element html
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return parent::_getElementHtml($element) . $this->_toHtml();
    }
}
