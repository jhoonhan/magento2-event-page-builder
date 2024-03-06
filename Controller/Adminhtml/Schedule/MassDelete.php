<?php
declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Controller\Adminhtml\Schedule;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use HanStudio\EventPageBuilder\Model\ResourceModel\Schedule\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;

use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\App\RequestInterface;

class MassDelete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'HanStudio_EventPageBuilder::schedule_delete';

    /** @var CollectionFactory */
    protected $collectionFactory;

    /** @var Filter */
    protected $filter;

    /** @var RequestInterface */
    protected $request;

    /**
     * MassDelete constructor.
     *
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     * @param RequestInterface $request
     */
    public function __construct(
        Context           $context,
        CollectionFactory $collectionFactory,
        Filter            $filter,
        RequestInterface  $request
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
        $this->request = $request;
    }

    /**
     * Schedule mass delete action
     *
     * @return Redirect
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(): Redirect
    {
        $collection = $this->collectionFactory->create();
        $items = $this->filter->getCollection($collection);
        $itemsSize = $items->getSize();

        $event_id = $this->request->getParam('event_id');

        foreach ($items as $item) {
            $item->delete();
        }

        $this->messageManager->addSuccessMessage(
            __('A total of %1 record(s) have been deleted.', $itemsSize)
        );

        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        // On success, redirect back to the relevant event/edit page using the event_id
        return $redirect->setPath('*/event/edit/event_id/' . $event_id);
    }
}
