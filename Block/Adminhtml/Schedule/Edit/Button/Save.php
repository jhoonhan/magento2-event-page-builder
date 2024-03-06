<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Block\Adminhtml\Schedule\Edit\Button;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\RequestInterface;

class Save implements ButtonProviderInterface
{
    /**
     * Build Urls
     *
     * @var UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * CustomButton constructor.
     * @param Context $context
     * @param RequestInterface $request
     */
    public function __construct(
        Context          $context,
        RequestInterface $request,
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->request = $request;
    }

    /**
     * Get the event_id from the request
     *
     * @return string
     */
    public function getEventId(): string
    {
        return (string)$this->request->getParam('event_id');
    }

    /**
     * Get the button data
     *
     * @return array
     */
    public function getButtonData(): array
    {
        $targetName = 'hanstudio_eventpagebuilder_schedule_form.hanstudio_eventpagebuilder_schedule_form';
        return [
            'label' => __('Save Schedule'),
            'class' => 'save primary',
            //  S009-1: This injects the event_id to the save action
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => $targetName,
                                'actionName' => 'save',
                                'params' => [
                                    true,
                                    ['event_id' => $this->getEventId()]
                                ],],]]]]
        ];
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl(string $route = '', array $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
