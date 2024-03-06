<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Controller\Adminhtml\Event;

use HanStudio\EventPageBuilder\Model\Event;
use HanStudio\EventPageBuilder\Model\EventFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Event as EventResource;

use HanStudio\EventPageBuilder\Model\ResourceModel\Schedule\CollectionFactory as ScheduleCollectionFactory;
use HanStudio\EventPageBuilder\Controller\Adminhtml\BlockActions;
use HanStudio\EventPageBuilder\Model\Config\Source\DateTimeFormat;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NotFoundException;

class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'HanStudio_EventPageBuilder::event_save';

    /**
     * @param Context $context
     * @param EventFactory $eventFactory
     * @param EventResource $eventResource
     * @param ScheduleCollectionFactory $scheduleCollectionFactory
     * @param BlockActions $blockActions
     * @param DateTimeFormat $dateTimeFormat
     */
    public function __construct(
        private Context                   $context,
        private EventFactory              $eventFactory,
        private EventResource             $eventResource,
        private ScheduleCollectionFactory $scheduleCollectionFactory,
        private BlockActions              $blockActions,
        private DateTimeFormat            $dateTimeFormat
    ) {
        parent::__construct($context);
    }

    /**
     * Execute the action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        $post = $this->getRequest()->getPost();
        $isExistingPost = $post->event_id;

        /** @var Event $event */
        $event = $this->eventFactory->create();

        if ($isExistingPost) {
            try {
                $this->eventResource->load($event, $post->event_id);
                if (!$event->getData('event_id')) {
                    throw new NotFoundException(__('This record no longer exists.'));
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $redirect->setPath('*/*/');
            }
        } else {
            // If new, build an object with the posted data to save it
            unset($post->event_id);
        }

        $event->setData(array_merge($event->getData(), $post->toArray()));
        //  Save the event to the database
        try {
            $this->eventResource->save($event);
            //  E010 - Manages creation and update of CMS Block
            $this->blockActions->updateCmsBlock($event, $post);

            $this->messageManager->addSuccessMessage(__('The record has been saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('There was a problem saving the record. ' . $e->getMessage()));
            return $redirect->setPath('*/*/');
        }

        // On success, redirect back to the admin grid
        if ($isExistingPost) {
            return $redirect->setPath("*/event/edit/event_id/$isExistingPost/");
        } else {
            return $redirect->setPath("*/*/");
        }
    }
}
