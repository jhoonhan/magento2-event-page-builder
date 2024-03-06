<?php
declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Controller\Adminhtml;

use Exception;
use HanStudio\EventPageBuilder\Model\RelationFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Relation as RelationResource;
use HanStudio\EventPageBuilder\Model\ResourceModel\Relation\CollectionFactory as RelationCollectionFactory;

class RelationSave
{
    public const ADMIN_RESOURCE = 'HanStudio_EventPageBuilder::schedule_save';

    /**
     * @param RelationFactory $relationFactory
     * @param RelationResource $relationResource
     * @param ScheduleCollectionFactory $relationCollectionFactory
     */
    public function __construct(
        private RelationFactory           $relationFactory,
        private RelationResource          $relationResource,
        private RelationCollectionFactory $relationCollectionFactory
    ) {
    }

    /**
     * Save the relation between event and schedule
     *
     * @param int $event_id
     * @param int $schedule_id
     * @param array $speaker_ids
     * @return void
     * @throws Exception
     */
    public function save(int $event_id, int $schedule_id, array|null $speaker_ids): void
    {
        //        Deletes everything that has matching schedule_id
        $collection = $this->relationCollectionFactory->create();
        $collection->addFieldToFilter('schedule_id', $schedule_id);
        $relationItems = $collection->getItems();
        if (!empty($relationItems)) {
            foreach ($relationItems as $relationItem) {
                $this->relationResource->delete($relationItem);
            }
        }

        //        Recreate the relations
        if (!empty($speaker_ids)) {
            foreach ($speaker_ids as $speaker_id) {
                $relation = $this->relationFactory->create()->setData([
                    'event_id' => $event_id,
                    'schedule_id' => $schedule_id,
                    'speaker_id' => $speaker_id
                ]);
                $this->relationResource->save($relation);
            }
        }
    }
}
