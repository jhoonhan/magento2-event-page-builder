<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Block\Adminhtml\Speaker\Edit\Button;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\RequestInterface;

class Save implements ButtonProviderInterface
{
    /**
     * Builds url for the page
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
     * Get button data
     *
     * @return int
     */
    public function getEventId(): int
    {
        return (int)$this->request->getParam('event_id');
    }

    /**
     * Get button data
     *
     * @return array
     */
    public function getButtonData(): array
    {
        $targetName = 'hanstudio_eventpagebuilder_speaker_form.hanstudio_eventpagebuilder_speaker_form';
        return [
            'label' => __('Save Speaker'),
            'class' => 'save primary',
//            This injects the event_id to the save action
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
                                ],]]]]]
        ];
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
