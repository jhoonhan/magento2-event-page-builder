<?php

namespace HanStudio\EventPageBuilder\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;

class BlockTemplate extends Template
{
    /**
     * BlockTemplate constructor.
     *
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array   $data = []
    ) {
        parent::__construct($context, $data);
        $this->setTemplate('HanStudio_EventPageBuilder::BlockTemplates/block-template.phtml');
    }

    /**
     * Get the html content
     *
     * @return string
     */

    public function getHtmlContent()
    {
        return $this->toHtml();
    }
}
