<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Controller\Adminhtml\Schedule;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use HanStudio\EventPageBuilder\Model\Schedule;
use HanStudio\EventPageBuilder\Model\ScheduleFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Schedule as ScheduleResource;
use HanStudio\EventPageBuilder\Model\Config\Source\DateTimeFormat;

class InlineEdit extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'HanStudio_EventPageBuilder::schedule_save';

    /**
     * InlineEdit constructor.
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param ScheduleFactory $scheduleFactory
     * @param ScheduleResource $scheduleResource
     * @param DateTimeFormat $dateTimeFormat
     */
    public function __construct(
        private Context          $context,
        private JsonFactory      $jsonFactory,
        private ScheduleFactory  $scheduleFactory,
        private ScheduleResource $scheduleResource,
        private DateTimeFormat   $dateTimeFormat,
    ) {
        parent::__construct($context);
    }

    /**
     * Save the inline edit data
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $json = $this->jsonFactory->create();
        $messages = [];
        $error = false;
        $isAjax = $this->getRequest()->getParam('isAjax', false);
        $items = $this->getRequest()->getParam('items', []);

        if (!$isAjax || !count($items)) {
            $messages[] = __('Please correct the data sent.');
            $error = true;
        }

        if (!$error) {
            foreach ($items as $item) {
                $schedule_id = $item['schedule_id'];

                try {
                    /** @var Schedule $schedule */
                    $schedule = $this->scheduleFactory->create();
                    $this->scheduleResource->load($schedule, $schedule_id);
                    //  S010-1: Validate Start End time input
                    if (!$this->dateTimeFormat->validateTime($item['starts_at']) ||
                        !$this->dateTimeFormat->validateTime($item['ends_at'])
                    ) {
                        $messages[] = __("The time must be in the format, ex: 13:30.");
                        $error = true;
                    } else {
                        $schedule->setData(array_merge($schedule->getData(), $item));
                        //  S010-2: Change the time data to match the timezone
                        $schedule->setData(
                            'schedule_date',
                            $this->dateTimeFormat->getDateTime($item['schedule_date'], $item['starts_at'])
                        );

                        $this->scheduleResource->save($schedule);
                    }
                } catch (\Exception $e) {
                    $messages[] = __("Something went wrong while saving item $schedule_id." . " " . $e->getMessage());
                    $error = true;
                }
            }
        }

        return $json->setData([
            'messages' => $messages,
            'error' => $error,
        ]);
    }
}
