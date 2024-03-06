<?php
declare(strict_types=1);

/**
 * K011-1
 * Event_id is injected to Speaker/Save action
 */

namespace HanStudio\EventPageBuilder\Test\Unit\Block\Adminhtml\Speaker\Edit\Button;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

use HanStudio\EventPageBuilder\Block\Adminhtml\Speaker\Edit\Button\Save;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\App\RequestInterface;

class SaveTest extends TestCase
{

    private const EVENT_ID = "0";

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
        $params = ['event_id' => self::EVENT_ID];

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
