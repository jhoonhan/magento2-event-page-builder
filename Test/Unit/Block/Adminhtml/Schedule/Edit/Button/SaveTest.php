<?php
declare(strict_types=1);

/**
 * S009-1
 * Test if buttonData returns correct params with matching event_id
 */

namespace HanStudio\EventPageBuilder\Test\Unit\Block\Adminhtml\Schedule\Edit\Button;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

use HanStudio\EventPageBuilder\Block\Adminhtml\Schedule\Edit\Button\Save;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\App\RequestInterface;

class SaveTest extends TestCase
{
    /**
     * @var Save
     */
    private Save $object;

    /**
     * @var MockObject
     */
    private MockObject $context;

    /**
     * @var MockObject
     */
    private MockObject $request;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->request = $this->getMockBuilder(RequestInterface::class)
            ->getMock();
        $params = ['event_id' => 0];

        $this->request->method('getParam')
            ->willReturnCallback(function ($paramName) use ($params) {
                return $params[$paramName] ?? null;
            });

        $this->object = new Save(
            $this->context,
            $this->request,
        );
    }

    /**
     * Test getButtonData
     *
     * @return void
     */
    public function testGetButtonData(): void
    {
        $event_id = $this->object->getEventId();
        $buttonDataAttributes =
            $this->object->getButtonData()['data_attribute']['mage-init']['buttonAdapter']['actions'][0];

        $this->assertEquals('save', $buttonDataAttributes['actionName']);
        $this->assertTrue($buttonDataAttributes['params'][0]);
        $this->assertEquals($event_id, $buttonDataAttributes['params'][1]['event_id']);
    }
}
