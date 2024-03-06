<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class ScheduleActions extends Column
{

    /** @var string */
    protected $mode;

    /**
     * Actions constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface     $context,
        UiComponentFactory   $uiComponentFactory,
        private UrlInterface $urlBuilder,
        array                $components = [],
        array                $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {

        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as & $item) {
            if (!isset($item['schedule_id'])) {
                continue;
            }
            $item[$this->getData('name')] = [
                'edit' => [
                    'href' => $this->urlBuilder->getUrl("eventpagebuilder/schedule/edit", [
                        'schedule_id' => $item['schedule_id'], 'event_id' => $item['event_id']
                    ]),
                    'label' => __('Edit'),
                ],
                'delete' => [
                    'href' => $this->urlBuilder->getUrl("eventpagebuilder/schedule/delete", [
                        'schedule_id' => $item['schedule_id'], 'event_id' => $item['event_id']
                    ]),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete %1', $item['name']),
                        'message' => __('Are you sure you want to delete the "%1" record?', $item['name']),
                    ],
                ],
            ];
        }

        return $dataSource;
    }
}
