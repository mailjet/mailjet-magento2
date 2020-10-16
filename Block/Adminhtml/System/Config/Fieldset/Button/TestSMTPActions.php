<?php

namespace Mailjet\Mailjet\Block\Adminhtml\System\Config\Fieldset\Button;

class TestSMTPActions extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var string
     */
    protected $_template = 'Mailjet_Mailjet::system/config/fieldset/button/testsmtpactions.phtml';

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
        $sendButton = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            [
                'id'     => \Mailjet\Mailjet\Helper\Data::FIELD_ID_ACCOUNT_TEST_SMTP_SEND_BUTTON,
                'style'  => 'background-color: #524d49; color: #f7f3eb;',
                'label'  => __('Send'),
            ]
        );

        $cancelbutton = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            [
                'id'     => \Mailjet\Mailjet\Helper\Data::FIELD_ID_ACCOUNT_TEST_SMTP_CANCEL_BUTTON,
                'label'  => __('Cancel'),
            ]
        );

        return $sendButton->toHtml() . $cancelbutton->toHtml();
    }
}
