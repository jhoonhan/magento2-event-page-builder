<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Block\Adminhtml\Speaker\New\Button;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class AddSpeaker implements ButtonProviderInterface
{

    /**
     * CustomButton constructor.
     *
     * @param Context $context
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        private Context          $context,
        private RequestInterface $request,
        public UrlInterface      $urlBuilder
    ) {
        $this->urlBuilder = $this->context->getUrlBuilder();
    }

    /**
     * Get button data
     *
     * @return array
     */
    public function getButtonData(): array
    {
        //  K011-1: Get event id from URL param & injects into speaker/new
        $event_id = $this->request->getParam('event_id');
        if (!$event_id) {
            return [
                'label' => __('ERROR: Return to Event List'),
                'class' => 'save primary',
                'on_click' => sprintf("location.href = '%s';", $this->getUrl('*/event/index')),
            ];
        } else {
            $url = $this->getUrl('eventpagebuilder/speaker/new', ['event_id' => $event_id]);
            return [
                'label' => __('Add Speaker'),
                'class' => 'save primary',
                'on_click' => sprintf("location.href = '%s';", $url),
            ];
        }
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
