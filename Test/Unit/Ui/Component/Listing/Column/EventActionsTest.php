<?php

namespace HanStudio\EventPageBuilder\Test\Unit\Ui\Component\Listing\Column;

use http\Url;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use HanStudio\EventPageBuilder\Ui\Component\Listing\Column\EventActions;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class EventActionsTest extends TestCase
{
    /**
     * @var EventActions
     */
    private EventActions $object;

    /**
     * @var MockObject
     */
    private MockObject $context;

    /**
     * @var MockObject
     */
    private MockObject $uiComponentFactory;

    /**
     * @var MockObject
     */
    private MockObject $urlBuilder;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->context = $this->getMockForAbstractClass(
            ContextInterface::class,
            [],
            '',
            false,
            false,
            true,
            []
        );
        $this->uiComponentFactory = $this->getMockBuilder(UiComponentFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlBuilder = $this->getMockForAbstractClass(
            UrlInterface::class,
            [],
            '',
            false,
            false,
            true,
            ['getUrl']
        );

        $this->object = new EventActions(
            $this->context,
            $this->uiComponentFactory,
            $this->urlBuilder
        );
    }

    /**
     * Test prepareDataSourceEmpty
     *
     * @return void
     */
    public function testPrepareDataSourceEmpty(): void
    {
        $dataSource = [];
        $this->assertEmpty($this->object->prepareDataSource($dataSource));
    }

    /**
     * Test prepareDataSourceLinks
     *
     * @return void
     */
    public function testPrepareDataSourceLinks(): void
    {
        $dataSource = [
            'data' => [
                'items' => [
                    [
                        'event_id' => 0,
                        'name' => 'Event Actions Test',
                        'url' => 'event-actions-test-url',
                        'event_date' => '2023-04-29 00:00:00',
                        'timezone' => 4,
                        'is_published' => 0,
                    ]
                ]
            ]
        ];
        $componentName = 'action';

        $this->object->setData('name', $componentName);

        $this->urlBuilder
            ->method('getUrl')
            ->withConsecutive(
                [
                    $this->equalTo('eventpagebuilder/event/edit'), $this->equalTo(['event_id' => 0])
                ],
                [
                    $this->equalTo('eventpagebuilder/event/delete'), $this->equalTo(['event_id' => 0])
                ]
            )
            ->willReturnOnConsecutiveCalls(
                'eventpagebuilder/event/edit/event_id/0',
                'eventpagebuilder/event/delete/event_id/0'
            );

        $result = $this->object->prepareDataSource($dataSource);

        $this->assertArrayHasKey('edit', $result['data']['items'][0][$componentName]);
        $this->assertArrayHasKey('delete', $result['data']['items'][0][$componentName]);
    }
}
