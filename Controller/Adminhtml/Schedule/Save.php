<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Controller\Adminhtml\Schedule;

use Laminas\Stdlib\ParametersInterface;
use Magento\Framework\Controller\ResultInterface;

use HanStudio\EventPageBuilder\Model\Config\Source\DateTimeFormat;
use HanStudio\EventPageBuilder\Model\Schedule;
use HanStudio\EventPageBuilder\Model\ScheduleFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Schedule as ScheduleResource;
use HanStudio\EventPageBuilder\Model\EventFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Event as EventResource;
use HanStudio\EventPageBuilder\Model\RelationFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Relation as RelationResource;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\NotFoundException;
use HanStudio\EventPageBuilder\Controller\Adminhtml\RelationSave;

class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'HanStudio_EventPageBuilder::schedule_save';

    /**
     * @param Context $context
     * @param ScheduleFactory $scheduleFactory
     * @param ScheduleResource $scheduleResource
     * @param JsonFactory $jsonFactory
     * @param EventFactory $eventFactory
     * @param EventResource $eventResource
     * @param RelationFactory $relationFactory
     * @param RelationResource $relationResource
     * @param RelationSave $relationSave
     * @param DateTimeFormat $dateTimeFormat
     */
    public function __construct(
        private Context          $context,
        private ScheduleFactory  $scheduleFactory,
        private ScheduleResource $scheduleResource,
        private JsonFactory      $jsonFactory,
        private EventFactory     $eventFactory,
        private EventResource    $eventResource,
        private RelationFactory  $relationFactory,
        private RelationResource $relationResource,
        private RelationSave     $relationSave,
        private DateTimeFormat   $dateTimeFormat
    ) {
        parent::__construct($context);
    }

    /**
     * Returns a redirect to the schedule list
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $post = $this->getRequest()->getPost();
        /** @var Schedule $schedule */
        $schedule = $this->scheduleFactory->create();
        $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        //        Ajax Validation
        $this->isAjaxCall($post);

        return $this->executeFunction($post, $schedule, $result);
    }

    /**
     * Check if the request is an ajax call
     *
     * @param ParametersInterface $post
     * @return void
     */
    private function isAjaxCall($post): void
    {
        //        Setting speakers object. Converting to JSON to save in the database
        if (!$this->getRequest()->getParam('isAjax', false)) {
            $speakers = $post->speakers;
        } else {
            $speakers = $this->getRequest()->getParam('data', []);
        }
        $this->getRequest()->setPostValue('speakers', $speakers);
    }

    /**
     * Saves data and return redirect
     *
     * @param Schedule $schedule
     * @param ParametersInterface $post
     * @param ResultInterface $result
     * @return Redirect
     */
    private function saveData(Schedule $schedule, ParametersInterface $post, ResultInterface $result): Redirect
    {
        try {
            $this->scheduleResource->save($schedule);
            //            Saves relation data between event, schedule and speaker
            $event_id = (int)$schedule->getData('event_id');
            $schedule_id = (int)$schedule->getData('schedule_id');
            $this->relationSave->save($event_id, $schedule_id, $post->speakers);

            $this->messageManager->addSuccessMessage(__('The record has been saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('There was a problem saving the record.'));
            return $result->setPath('*/*/');
        }
        return $result->setPath('*/event/edit/event_id/' . $post->event_id);
    }

    /**
     * Refactored function to execute the save function
     *
     * @param ParametersInterface $post
     * @param Schedule $schedule
     * @param ResultInterface $result
     * @return Redirect
     */
    private function executeFunction(ParametersInterface $post, Schedule $schedule, ResultInterface $result): Redirect
    {
        //        Check if the post is an existing record
        if ($post->schedule_id) {
            try {
                $this->scheduleResource->load($schedule, $post->schedule_id);
                if (!$schedule->getData('schedule_id')) {
                    throw new NotFoundException(__('This record no longer exists.'));
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $result->setPath('*/*/');
            }
        } else {
            // If new, build an object with the posted data to save it
            unset($post->schedule_id);
        }

        //  S010-1: Validate Start and End time input
        if (!$this->dateTimeFormat->validateTime($post->starts_at) ||
            !$this->dateTimeFormat->validateTime($post->ends_at)
        ) {
            $errorMsg = 'The start time is not valid. It must be in the format, ex: 13:30';
            $this->messageManager->addErrorMessage(__($errorMsg));
            if ($post->schedule_id) {
                return $result->setPath("*/schedule/edit/schedule_id/$post->schedule_id/event_id/$post->event_id/");
            } else {
                return $result->setPath("*/schedule/new/event_id/$post->event_id/");
            }
        }

        //        Set the data
        $schedule->setData(array_merge($schedule->getData(), $post->toArray()));

        //        S010-2: Change the time data to match the timezone
        $schedule->setData(
            'schedule_date',
            $this->dateTimeFormat->getDateTime($post->schedule_date, $post->starts_at)
        );

        //        Save the data
        return $this->saveData($schedule, $post, $result);
    }
}
