<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Controller\Adminhtml\Event;

use HanStudio\EventPageBuilder\Model\Event;
use HanStudio\EventPageBuilder\Model\EventFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Event as EventResource;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use HanStudio\EventPageBuilder\Controller\Adminhtml\BlockActions;

class Delete extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'HanStudio_EventPageBuilder::event_delete';

    /** @var EventFactory */
    protected $eventFactory;

    /** @var EventResource */
    protected $eventResource;

    /** @var BlockActions */
    protected $blockActions;

    /**
     * Delete constructor.
     * @param Context $context
     * @param EventFactory $eventFactory
     * @param EventResource $eventResource
     * @param BlockActions $blockActions
     */
    public function __construct(
        Context       $context,
        EventFactory  $eventFactory,
        EventResource $eventResource,
        BlockActions  $blockActions
    ) {
        parent::__construct($context);
        $this->eventFactory = $eventFactory;
        $this->eventResource = $eventResource;
        $this->blockActions = $blockActions;
    }

    /**
     * Execute action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        try {
            $id = $this->getRequest()->getParam('event_id');
            /** @var Event $event */
            $event = $this->eventFactory->create();
            $this->eventResource->load($event, $id);
            if ($event->getData('event_id')) {
                $this->eventResource->delete($event);
                //  E010-1: Delete corresponding CMS block
                $this->blockActions->deleteCmsBlock($event->getData('url'));

                $this->messageManager->addSuccessMessage(__('The record has been deleted.'));
            } else {
                $this->messageManager->addErrorMessage(__('The record does not exist.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $redirect->setPath('*/*');
    }
}
