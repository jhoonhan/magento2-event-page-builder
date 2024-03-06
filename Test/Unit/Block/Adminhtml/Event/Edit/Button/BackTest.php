<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Test\Unit\Block\Adminhtml\Event\Edit\Button;

use HanStudio\EventPageBuilder\Block\Adminhtml\Event\Edit\Button\Back;
use PHPUnit\Framework\TestCase;
use Magento\Framework\UrlInterface;

use PHPUnit\Framework\MockObject\MockObject;

class BackTest extends TestCase
{
    /**
     * @var Back
     */
    private Back $object;

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
        $this->urlBuilder = $this->getMockBuilder(UrlInterface::class)
            ->getMock();
        $this->object = new Back($this->urlBuilder);
    }

    /**
     * Test if buttonData returns correct params with matching event_id
     *
     * @return void
     */
    public function testGetButtonData(): void
    {
        $url = $this->object->urlBuilder->getUrl('*/*/');
        $this->assertEquals([
            'label' => __('Back'),
            'class' => 'back',
            'on_click' => sprintf("location.href = '%s';", $url),
        ], $this->object->getButtonData());
    }
}
