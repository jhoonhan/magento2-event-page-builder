<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Controller\Adminhtml\Speaker;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'HanStudio_EventPageBuilder::speaker_save';

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context             $context,
        private PageFactory $pageFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Return the page to edit a speaker
     *
     * @return Page
     */
    public function execute(): Page
    {
        $page = $this->pageFactory->create();
        $page->setActiveMenu('HanStudio_EventPageBuilder::event');
        $page->getConfig()->getTitle()->prepend(__('Edit Speaker'));

        return $page;
    }
}
