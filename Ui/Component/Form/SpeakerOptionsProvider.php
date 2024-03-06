<?php

namespace HanStudio\EventPageBuilder\Ui\Component\Form;

use Magento\Framework\Data\OptionSourceInterface;
use HanStudio\EventPageBuilder\Model\ResourceModel\Speaker\CollectionFactory;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\App\RequestInterface;

class SpeakerOptionsProvider implements OptionSourceInterface
{

    /** @var array */
    private $options;

    /**
     * Constructor
     *
     * @param CollectionFactory $collectionFactory
     * @param DataObject $objectConverter
     * @param RequestInterface $request
     */
    public function __construct(
        private CollectionFactory $collectionFactory,
        private DataObject        $objectConverter,
        private RequestInterface  $request
    ) {
    }

    /**
     * Get formatted name
     *
     * @param array $item
     * @return string
     */
    private function getFormattedName($item): string
    {
        $formattedName = $item['lastname'] . ', ' . $item['firstname'];
//            Only Title
        if ($item['title'] !== null && $item['company'] === null) {
            $formattedName .= ' (' . $item['title'] . ')';
        }
//            Only Company
        if ($item['title'] === null && $item['company'] !== null) {
            $formattedName .= ' (' . $item['company'] . ')';
        }
//            Title and Company
        if ($item['title'] !== null && $item['company'] !== null) {
            $formattedName .= ' (' . $item['title'] . ', ' . $item['company'] . ')';
        }
        return $formattedName;
    }

    /**
     * To option array
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $collection = $this->collectionFactory->create();
//        Filter options by event_id
        $eventId = $this->request->getParam('event_id');
        $collection->addFieldToFilter('event_id', $eventId);
        $items = $collection->getItems();

        uasort($items, function ($a, $b) {
            return strcmp($a['lastname'], $b['lastname']);
        });
        foreach ($items as $item) {
            $formattedName = $this->getFormattedName($item);

            $this->options[] = ['label' => $formattedName, 'value' => $item['speaker_id'], 'true' => 1];
        }
//        ERROR 001: If no speakers are found, the following line will return empty array
        if (empty($this->options)) {
            return [];
        } else {
            return $this->options;
        }
    }
}
