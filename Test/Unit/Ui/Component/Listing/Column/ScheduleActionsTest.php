<?php
declare(strict_types=1);

/**
 * S0090-4
 * Event_id injection to Schedule/Edit & Schedule/Delete
 */

namespace HanStudio\EventPageBuilder\Test\Unit\Ui\Component\Listing\Column;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use HanStudio\EventPageBuilder\Ui\Component\Listing\Column\ScheduleActions;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class ScheduleActionsTest extends TestCase
{
    private const MOCK_ID = 0;

    /**
     * @var ScheduleActions
     */
    private ScheduleActions $object;

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

        $this->object = new ScheduleActions(
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
                        'schedule_id' => self::MOCK_ID,
                        'event_id' => self::MOCK_ID,
                        'name' => 'Schedule Test',
                        'url' => 'schedule-test-url',
                        'schedule_date' => '2023-04-29 00:00:00',
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
                    $this->equalTo('eventpagebuilder/schedule/edit'),
                    $this->equalTo(['schedule_id' => self::MOCK_ID, 'event_id' => self::MOCK_ID])
                ],
                [
                    $this->equalTo('eventpagebuilder/schedule/delete'),
                    $this->equalTo(['schedule_id' => self::MOCK_ID, 'event_id' => self::MOCK_ID])
                ]
            )
            ->willReturnOnConsecutiveCalls(
                'eventpagebuilder/schedule/edit/schedule_id/0/event_id/0',
                'eventpagebuilder/schedule/delete/schedule_id/0/event_id/0'
            );

        $result = $this->object->prepareDataSource($dataSource)['data']['items'][0][$componentName];

        $this->assertArrayHasKey('edit', $result);
        $this->assertArrayHasKey('delete', $result);
    }
}
