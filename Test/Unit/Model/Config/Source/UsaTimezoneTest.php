<?php
declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Test\Unit\Model\Config\Source;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

use HanStudio\EventPageBuilder\Model\Config\Source\UsaTimezone;

class UsaTimezoneTest extends TestCase
{

    /**
     * @var UsaTimezone
     */
    private UsaTimezone $object;

    protected function setUp(): void
    {
        $this->object = new UsaTimezone();
    }

    /**
     * Test toOptionArray
     *
     * @return void
     */
    public function testToOptionArray()
    {
        $this->assertEquals([
            ['value' => 0, 'label' => 'AKT (Alaska)'],
            ['value' => 1, 'label' => 'PT (Pacific)'],
            ['value' => 2, 'label' => 'MT (Mountain)'],
            ['value' => 3, 'label' => 'CT (Central)'],
            ['value' => 4, 'label' => 'ET (Eastern)'],
            ['value' => 5, 'label' => 'AST (Atlantic)'],
            ['value' => 6, 'label' => 'HST (Hawaii)']
        ], $this->object->toOptionArray());
    }

    /**
     * Test toArray
     *
     * @return void
     */
    public function testToArray()
    {
        $this->assertEquals([
            0 => 'America/Anchorage',
            1 => 'America/Los_Angeles',
            2 => 'America/Denver',
            3 => 'America/Chicago',
            4 => 'America/New_York',
            5 => 'America/Puerto_Rico',
            6 => 'Pacific/Honolulu'
        ], $this->object->toArray());
    }

    /**
     * Test getTimezoneValue
     *
     * @return void
     */
    public function testGetTimezoneValue()
    {
        $this->assertEquals('America/Anchorage', $this->object->getTimezoneValue(0));
        $this->assertEquals('America/Los_Angeles', $this->object->getTimezoneValue(1));
        $this->assertEquals('America/Denver', $this->object->getTimezoneValue(2));
        $this->assertEquals('America/Chicago', $this->object->getTimezoneValue(3));
        $this->assertEquals('America/New_York', $this->object->getTimezoneValue(4));
        $this->assertEquals('America/Puerto_Rico', $this->object->getTimezoneValue(5));
        $this->assertEquals('Pacific/Honolulu', $this->object->getTimezoneValue(6));
    }
}
