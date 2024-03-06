<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Controller\Adminhtml\Schedule;

use HanStudio\EventPageBuilder\Model\Schedule;
use HanStudio\EventPageBuilder\Model\ScheduleFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Schedule as ScheduleResource;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Delete extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'HanStudio_EventPageBuilder::schedule_delete';

    /** @var ScheduleFactory */
    protected $scheduleFactory;

    /** @var ScheduleResource */
    protected $scheduleResource;

    /**
     * Delete constructor.
     *
     * @param Context $context
     * @param ScheduleFactory $scheduleFactory
     * @param ScheduleResource $scheduleResource
     */
    public function __construct(
        Context          $context,
        ScheduleFactory  $scheduleFactory,
        ScheduleResource $scheduleResource
    ) {
        $this->scheduleFactory = $scheduleFactory;
        $this->scheduleResource = $scheduleResource;
        parent::__construct($context);
    }

    /**
     * Execute the delete action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        $schedule = $this->scheduleFactory->create();
        try {
            $schedule_id = $this->getRequest()->getParam('schedule_id');
            /** @var Schedule $schedule */
            $this->scheduleResource->load($schedule, $schedule_id);
            if ($schedule->getData('schedule_id')) {
                $this->scheduleResource->delete($schedule);
                $this->messageManager->addSuccessMessage(__('The record has been deleted.'));
            } else {
                $this->messageManager->addErrorMessage(__('The record does not exist.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        // On success, redirect back to the relevant event/edit page using the event_id
        return $redirect->setPath('*/event/edit/event_id/' . $schedule->getData('event_id'));
    }
}
