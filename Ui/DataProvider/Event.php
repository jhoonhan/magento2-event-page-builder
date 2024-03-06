<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Ui\DataProvider;

use HanStudio\EventPageBuilder\Model\ResourceModel\Event\Collection;
use HanStudio\EventPageBuilder\Model\ResourceModel\Event\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class Event extends AbstractDataProvider
{

    /** @var array */
    private array $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string                    $name,
        string                    $primaryFieldName,
        string                    $requestFieldName,
        private CollectionFactory $collectionFactory,
        array                     $meta = [],
        array                     $data = []
    ) {
        $this->collection = $this->collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
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

            foreach ($this->collection->getItems() as $item) {
                $this->loadedData[$item->getData('event_id')] = $item->getData();
            }
        }

        return $this->loadedData;
    }
}
