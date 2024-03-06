<?php
declare(strict_types=1);

/**
 * E009: Datetime Timezone Setter
 */

namespace HanStudio\EventPageBuilder\Model\Config\Source;

use DateTime;
use DateTimeZone;
use HanStudio\EventPageBuilder\Model\Schedule;
use HanStudio\EventPageBuilder\Model\ResourceModel\Schedule\CollectionFactory as ScheduleCollectionFactory;
use HanStudio\EventPageBuilder\Model\EventFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Event as EventResource;

class DateTimeFormat
{
    /**
     * DateTimeFormat constructor.
     *
     * @param EventFactory $eventFactory
     * @param EventResource $eventResource
     * @param UsaTimezone $timezone
     * @param ScheduleCollectionFactory $scheduleCollectionFactory
     */
    public function __construct(
        private EventFactory              $eventFactory,
        private EventResource             $eventResource,
        private UsaTimezone               $timezone,
        private ScheduleCollectionFactory $scheduleCollectionFactory
    ) {
    }

    /**
     * S010-2: Get the new time data
     *
     * @param string $rawDate
     * @param string|null $time
     * @return string|null
     */
    public function getDateTime(string $rawDate, string|null $time): string|null
    {
        //  $rawDate step is necessary as inline-edit does not go through the formatting of input dateTime object.
        $rawDate = new DateTime($rawDate);
        $date = $rawDate->format('Y-m-d');
        $startCombinedDateTime = $date . ' ' . ($time ?? '00:00:00');
//        E009: Change time to UTC equivalent
//        $startTime = new DateTime($startCombinedDateTime, new DateTimeZone($timezone));
//        $startTime->setTimezone(new DateTimeZone('UTC'));
        $startTime = new DateTime($startCombinedDateTime);
        return $startTime->format('Y-m-d H:i:s');
    }

    /**
     * S010-1: Validates the time string
     *
     * @param string $timeString
     * @return bool
     */
    public function validateTime(string $timeString): bool
    {
        if (!$timeString) {
            return true;
        }
        $pattern1 = '/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/';
        $pattern2 = '/^(?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/';
        if (preg_match($pattern1, $timeString) === 1 || preg_match($pattern2, $timeString) === 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Gets the event timezone
     *
     * @param int $eventId
     * @return string
     */
    public function getEventTimezone(int $eventId): string
    {
        $event = $this->eventFactory->create();
        $event->load($eventId);
        return $this->timezone->getTimezoneValue($event->getData('timezone'));
    }

//    /**
//     * E009: Changes the "date" value of the schedule items
//             if the event timezone changes so that when user changes
//             timezone on the event, it reflects on the schedule items
//     * @param string $id
//     * @param string $orgTimezone
//     * @param string $newTimezone
//     * @return void
//     */
//    DISABLED
//    public function reformatScheduleTimes($event_id, $orgTimezone, $newTimezone): void
//    {
//        if ($orgTimezone !== $newTimezone) {
//            $collection = $this->scheduleCollectionFactory->create();
//            $collection->addFieldToFilter('event_id', $event_id);
//            $items = $collection->getItems();
//
//            foreach ($items as $item) {
//                $date = $item['schedule_date'];
//                $time = $item['starts_at'];
//                $dateTime = $this->getDateTime($date, $time);
//                $item->setData('schedule_date', $dateTime);
//                $item->save();
//            }
//        }
//    }
}
