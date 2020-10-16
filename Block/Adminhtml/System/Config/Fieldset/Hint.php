<?php

namespace Mailjet\Mailjet\Block\Adminhtml\System\Config\Fieldset;

class Hint extends \Magento\Backend\Block\Template implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * @var string
     */
    protected $_template = 'Mailjet_Mailjet::system/config/fieldset/hint.phtml';

    /**
     * @var \Magento\Framework\Module\ResourceInterface $moduleResource
     */
    private $moduleResource;

    /**
     * Hint constructor.
     * @param \Magento\Framework\Module\ResourceInterface $moduleResource
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Module\ResourceInterface $moduleResource,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->moduleResource = $moduleResource;

        parent::__construct($context, $data);
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return mixed
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->toHtml();
    }

    public function getModuleVersion()
    {
        return $this->moduleResource->getDbVersion(\Mailjet\Mailjet\Helper\Data::MODULE_NAME);
    }
}
