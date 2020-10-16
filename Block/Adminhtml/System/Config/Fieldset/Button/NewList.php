<?php

namespace Mailjet\Mailjet\Block\Adminhtml\System\Config\Fieldset\Button;

class NewList extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var string
     */
    protected $_template = 'Mailjet_Mailjet::system/config/fieldset/button/newlist.phtml';

    /**
     * Remove scope label
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Return element html
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * Generate collect button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $buttonNew = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            [
                'id'     => \Mailjet\Mailjet\Helper\Data::FIELD_ID_ACCOUNT_MAILJET_NEW_LIST_BUTTON,
                'style'  => 'background-color: #524d49; color: #f7f3eb;',
                'label'  => __('Create list'),
            ]
        );

        $buttonCancel = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            [
                'id'     => \Mailjet\Mailjet\Helper\Data::FIELD_ID_ACCOUNT_MAILJET_CANCEL_LIST_BUTTON,
                'label'  => __('Cancel'),
            ]
        );

        return $buttonNew->toHtml() . $buttonCancel->toHtml();
    }
}
