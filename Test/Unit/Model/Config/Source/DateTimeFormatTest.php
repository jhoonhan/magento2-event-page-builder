<?php
declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Test\Unit\Model\Config\Source;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

use HanStudio\EventPageBuilder\Model\EventFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Event as EventResource;
use HanStudio\EventPageBuilder\Model\ResourceModel\Schedule\CollectionFactory as ScheduleCollectionFactory;
use HanStudio\EventPageBuilder\Model\Config\Source\UsaTimezone;
use HanStudio\EventPageBuilder\Model\Config\Source\DateTimeFormat;

class DateTimeFormatTest extends TestCase
{
    /**
     * @var DateTimeFormat
     */
    private DateTimeFormat $object;

    /**
     * @var MockObject
     */
    private MockObject $eventFactory;

    /**
     * @var MockObject
     */
    private MockObject $eventResource;

    /**
     * @var MockObject
     */
    private MockObject $timezone;

    /**
     * @var MockObject
     */
    private MockObject $scheduleCollectionFactory;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->eventFactory = $this
            ->getMockBuilder(EventFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventResource = $this
            ->getMockBuilder(EventResource::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->timezone = $this
            ->getMockBuilder(UsaTimezone::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->scheduleCollectionFactory = $this
            ->getMockBuilder(ScheduleCollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new DateTimeFormat(
            $this->eventFactory,
            $this->eventResource,
            $this->timezone,
            $this->scheduleCollectionFactory
        );
    }

    /**
     * S010-1: Test getDateTime
     *
     * @return void
     */
    public function testValidateTime(): void
    {
        $mock_time_true = "09:00";
        $mock_time_false_1 = "09;00";
        $mock_time_false_2 = "109:00";

        $result1 = $this->object->validateTime($mock_time_true);
        $result2 = $this->object->validateTime($mock_time_false_1);
        $result3 = $this->object->validateTime($mock_time_false_2);

        $this->assertTrue($result1);
        $this->assertFalse($result2);
        $this->assertFalse($result3);
    }

    /**
     * S010-2: Test validateTimes
     *
     * @return void
     */
    public function testGetDateTime(): void
    {
        $mock_raw_date = "2021-12-31";
        $mock_time = "09:00";
        $mock_time_null = null;

        $result1 = $this->object->getDateTime($mock_raw_date, $mock_time);
        $result2 = $this->object->getDateTime($mock_raw_date, $mock_time_null);

        $this->assertEquals("2021-12-31 09:00:00", $result1);
        $this->assertEquals("2021-12-31 00:00:00", $result2);
    }
}
