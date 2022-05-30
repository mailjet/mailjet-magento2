<?php

namespace Mailjet\Mailjet\Block\Adminhtml\System\Config\Fieldset;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

class Hint extends \Magento\Backend\Block\Template implements RendererInterface
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
     *
     * @param \Magento\Framework\Module\ResourceInterface $moduleResource
     * @param \Magento\Backend\Block\Template\Context     $context
     * @param array                                       $data
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
     * Render
     *
     * @param  AbstractElement $element
     * @return mixed
     */
    public function render(AbstractElement $element)
    {
        return $this->toHtml();
    }

    /**
     * Get Module Version
     *
     * @return false|string
     */
    public function getModuleVersion()
    {
        return $this->moduleResource->getDbVersion(\Mailjet\Mailjet\Helper\Data::MODULE_NAME);
    }
}
