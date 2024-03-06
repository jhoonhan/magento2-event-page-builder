<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Block\Adminhtml\Schedule\New\Button;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class AddSchedule implements ButtonProviderInterface
{
    /**
     * Builds Urls
     *
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * AddSchedule constructor.
     *
     * @param Context $context
     * @param RequestInterface $request
     */
    public function __construct(
        private Context          $context,
        private RequestInterface $request,
    ) {
        $this->urlBuilder = $this->context->getUrlBuilder();
    }

    /**
     * Returns the button data
     *
     * @return array
     */
    public function getButtonData(): array
    {
        //  S009-2: Get event id from URL param & injects into schedule/new
        $event_id = $this->request->getParam('event_id');
        if (!$event_id) {
            $url = $this->getUrl('*/event/index', ['event_id' => $event_id]);
            return [
                'label' => __('ERROR: Return to Event List'),
                'class' => 'save primary',
                'on_click' => sprintf("location.href = '%s';", $url),
            ];
        } else {
            $url = $this->getUrl('eventpagebuilder/schedule/new', ['event_id' => $event_id]);
            return [
                'label' => __('Add Schedule'),
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
    public function getUrl(string $route = '', array $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
