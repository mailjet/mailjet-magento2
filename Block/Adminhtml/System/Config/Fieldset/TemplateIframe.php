<?php

namespace Mailjet\Mailjet\Block\Adminhtml\System\Config\Fieldset;

class TemplateIframe extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var string
     */
    protected $_template = 'Mailjet_Mailjet::system/config/fieldset/templateiframe.phtml';
    protected $_element = null;

    /**
     * Return element html
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->_element = $element;
        return $element->getElementHtml() . $this->_toHtml();
    }

    /**
     * Generate collect button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            [
                'id'     => $this->getButtonId(),
                'label'  => __('Edit and preview'),
            ]
        );

        return $button->toHtml();
    }

    public function getButtonId()
    {
        return $this->getElementId() . '_button';
    }

    public function getElementId()
    {
        return $this->_element->getHtmlId();
    }
}
