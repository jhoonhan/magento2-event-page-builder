<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Ui\DataProvider;

use HanStudio\EventPageBuilder\Model\ResourceModel\Speaker\Collection;
use HanStudio\EventPageBuilder\Model\ResourceModel\Speaker\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

class Speaker extends AbstractDataProvider
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
     * @param StoreManagerInterface $storeManager ,
     * @param UrlInterface $urlInterface ,
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string                        $name,
        string                        $primaryFieldName,
        string                        $requestFieldName,
        private CollectionFactory     $collectionFactory,
        private StoreManagerInterface $storeManager,
        private UrlInterface          $urlInterface,
        array                         $meta = [],
        array                         $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $this->collectionFactory->create();
    }

    /**
     * Extracts event id from a url
     *
     * @return string
     */
    private function getEventId(): string
    {
        $url = $this->urlInterface->getCurrentUrl();

        $pattern = '/\/event_id\/(\d+)/';
        if (preg_match($pattern, $url, $matches)) {
            $idValue = $matches[1];
            return $idValue;
        } else {
            return "0";
        }
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

//            Adds image so that the edit form can see already saved image
            foreach ($this->collection->getItems() as $item) {
                $this->loadedData[$item->getData('speaker_id')] = $item->getData();
                if ($item->getData('image')) {
                    $imageData = json_decode($item->getData('image'), true);
                    $i['image'][0]['name'] = $imageData['name'];
                    $i['image'][0]['url'] = $imageData['url'];
                    $i['image'][0]['type'] = $imageData['type'];
                    $i['image'][0]['size'] = $imageData['size'];

                    $fullData = $this->loadedData;
                    $mergedData = array_merge($fullData[$item->getData('speaker_id')], $i);
                    $this->loadedData[$item->getData('speaker_id')] = $mergedData;
                }
            }
        }

        return $this->loadedData;
    }

    /**
     * Get media url
     *
     * @param string $path
     * @return string
     */
    public function getMediaUrl(string $path = ''): string
    {
        $formattedUrl = 'wysiwyg/eventpagebuilder/';

        return $this->storeManager
                ->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $formattedUrl . $path;
    }
}
