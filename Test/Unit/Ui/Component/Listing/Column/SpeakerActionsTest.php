<?php
declare(strict_types=1);

/**
 * K0011-4
 * Event_id injection to Speaker/Edit & Speaker/Delete
 */

namespace HanStudio\EventPageBuilder\Test\Unit\Ui\Component\Listing\Column;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use HanStudio\EventPageBuilder\Ui\Component\Listing\Column\SpeakerActions;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class SpeakerActionsTest extends TestCase
{
    private const MOCK_ID = 0;
    private const MOCK_DATA = [
        'speaker_id' => self::MOCK_ID,
        'event_id' => self::MOCK_ID,
        'firstname' => 'Speaker',
        'lastname' => 'Test'
    ];

    /**
     * @var SpeakerActions
     */
    private SpeakerActions $object;

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

        $this->object = new SpeakerActions(
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
                    self::MOCK_DATA
                ]
            ]
        ];
        $componentName = 'action';
        $this->object->setData('name', $componentName);

        $this->urlBuilder
            ->method('getUrl')
            ->withConsecutive(
                [
                    $this->equalTo('eventpagebuilder/speaker/edit'),
                    $this->equalTo(['speaker_id' => self::MOCK_ID, 'event_id' => self::MOCK_ID])
                ],
                [
                    $this->equalTo('eventpagebuilder/speaker/delete'),
                    $this->equalTo(['speaker_id' => self::MOCK_ID, 'event_id' => self::MOCK_ID])
                ]
            )
            ->willReturnOnConsecutiveCalls(
                'eventpagebuilder/speaker/edit/speaker_id/0/event_id/0',
                'eventpagebuilder/speaker/delete/speaker_id/0/event_id/0'
            );

        $result = $this->object->prepareDataSource($dataSource)['data']['items'][0][$componentName];

        $this->assertArrayHasKey('edit', $result);
        $this->assertArrayHasKey('delete', $result);
    }
}
