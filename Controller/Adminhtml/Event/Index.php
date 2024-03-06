<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Controller\Adminhtml\Event;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'HanStudio_EventPageBuilder::event';

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context             $context,
        private PageFactory $pageFactory,
    ) {
        parent::__construct($context);
    }

    /**
     * Returns Events page
     *
     * @return Page
     */
    public function execute(): Page
    {
        $page = $this->pageFactory->create();
        $page->setActiveMenu('HanStudio_EventPageBuilder::event');
        $page->getConfig()->getTitle()->prepend(__('Events'));

        return $page;
    }
}
