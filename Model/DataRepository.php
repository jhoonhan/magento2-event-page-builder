<?php
declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Model;

use HanStudio\EventPageBuilder\Model\EventFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Event as EventResource;

use HanStudio\EventPageBuilder\Model\Schedule;
use HanStudio\EventPageBuilder\Model\ResourceModel\Schedule\CollectionFactory as ScheduleCollectionFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Schedule as ScheduleResource;

use HanStudio\EventPageBuilder\Model\SpeakerFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Speaker as SpeakerResource;

use HanStudio\EventPageBuilder\Model\RelationFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Relation\CollectionFactory as RelationCollectionFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Relation as RelationResource;

use HanStudio\EventPageBuilder\Model\Data;
use HanStudio\EventPageBuilder\Api\Data\DataInterface;
use HanStudio\EventPageBuilder\Api\DataRepositoryInterface;

use Magento\Framework\Exception\NoSuchEntityException;

class DataRepository implements DataRepositoryInterface
{

    /**
     * EventRepository constructor.
     * @param EventFactory $eventFactory
     * @param EventResource $eventResource
     * @param ScheduleCollectionFactory $scheduleCollectionFactory
     * @param ScheduleResource $scheduleResource
     * @param SpeakerFactory $speakerFactory
     * @param SpeakerResource $speakerResource
     * @param RelationFactory $relationFactory
     * @param RelationResource $relationResource
     * @param RelationCollectionFactory $relationCollectionFactory
     */
    public function __construct(
        private EventFactory              $eventFactory,
        private EventResource             $eventResource,
        private ScheduleCollectionFactory $scheduleCollectionFactory,
        private ScheduleResource          $scheduleResource,
        private SpeakerFactory            $speakerFactory,
        private SpeakerResource           $speakerResource,
        private RelationFactory           $relationFactory,
        private RelationResource          $relationResource,
        private RelationCollectionFactory $relationCollectionFactory,
    ) {
    }

    /**
     * A001: Injects Speakers and returns formatted schedules array
     *
     * @param int $event_id
     * @return array
     */
    private function getSchedules(int $event_id): array
    {
        $schedulesFormatted = [];

        $scheduleCollection = $this->scheduleCollectionFactory->create();
        $scheduleCollection->addFieldToFilter('event_id', $event_id);
        $scheduleCollection->setOrder('schedule_date', 'ASC');
        $schedules = $scheduleCollection->getItems();

        $relationCollection = $this->relationCollectionFactory->create();
        $relationCollection->addFieldToFilter('event_id', $event_id);
        $relations = $relationCollection->getItems();

        foreach ($schedules as $schedule) {
            $speakers = [];
            //            Filter array of relations by schedule_id
            $filteredRelations = array_filter(
                $relations,
                function ($relation) use ($schedule) {
                    return (int)$relation->getData('schedule_id') === (int)$schedule['schedule_id'];
                }
            );
            //            Uses relation table to get speakers
            foreach ($filteredRelations as $relation) {
                $speaker = $this->speakerFactory->create();
                $this->speakerResource->load($speaker, $relation->getData('speaker_id'));
                $speakers[] = $speaker->getData();
            }
            $schedule_date = $schedule['schedule_date'];
            // Split the string by space
            $date = explode(' ', $schedule_date)[0];
            $schedule->setData('speakers', $speakers);
            $schedulesFormatted[$date][] = $schedule->getData();
        }
        return $schedulesFormatted;
    }

    /**
     * This function is responsible for collecting data from three tables and returning it as a combined json string
     *
     * @param int $event_id
     * @return string
     * @throws NoSuchEntityException
     */
    public function getById(int $event_id): string
    {
        /** @var Event $event */
        $event = $this->eventFactory->create();
        $this->eventResource->load($event, $event_id);
        if (!$event->getData('event_id')) {
            throw new NoSuchEntityException(__('Event with id "%1" does not exist.', $event_id));
        }

//        Gets formatted schedule array
        $schedulesFormatted = $this->getSchedules($event_id);

        $event->setData('schedules', $schedulesFormatted);
//        Return as json
        return json_encode($event->getData());
    }

    public function image($id): string
    {
        return 'image';
    }
}
