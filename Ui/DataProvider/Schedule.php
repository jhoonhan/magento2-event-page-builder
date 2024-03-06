<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Ui\DataProvider;

use HanStudio\EventPageBuilder\Model\ResourceModel\Schedule\Collection;
use HanStudio\EventPageBuilder\Model\ResourceModel\Schedule\CollectionFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Relation\CollectionFactory as RelationCollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class Schedule extends AbstractDataProvider
{
    /** @var Collection $collection */
    protected $collection;

    /** @var array */
    private array $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RelationCollectionFactory $relationCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string                            $name,
        string                            $primaryFieldName,
        string                            $requestFieldName,
        private CollectionFactory         $collectionFactory,
        private RelationCollectionFactory $relationCollectionFactory,
        array                             $meta = [],
        array                             $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $this->collectionFactory->create();
    }

    /**
     * This feeds related speakers from the relation table to the schedule_form and to the ui-select
     *
     * @param int $schedule_id
     * @return array
     */
    private function getSpeakerIds(int $schedule_id): array
    {
        $collection = $this->relationCollectionFactory->create();
        $collection->addFieldToFilter('schedule_id', $schedule_id);
        $realtionItems = $collection->getItems();

        $speakerIds = [];
        foreach ($realtionItems as $realtionItem) {
            $speakerIds[] = $realtionItem->getData('speaker_id');
        }

        return $speakerIds;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData(): array
    {
        if (!isset($this->loadedData)) {
            $this->loadedData = [];
            $items = $this->collection->getItems();
            foreach ($items as $item) {
                $schedule_id = (int)$item->getData('schedule_id');
                //  K009-1: This feeds related speakers from the relation table to the ui-select
                $speakerIds = $this->getSpeakerIds($schedule_id);

                $this->loadedData[$schedule_id] = $item->getData();
                $this->loadedData[$schedule_id]['speakers'] = $speakerIds;
            }
        }

        return $this->loadedData;
    }
}
