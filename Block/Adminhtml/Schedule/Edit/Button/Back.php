<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Block\Adminhtml\Schedule\Edit\Button;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\App\RequestInterface;

class Back implements ButtonProviderInterface
{
    /** @var UrlInterface $urlBuilder */
    private UrlInterface $urlBuilder;

    /**
     * Back constructor.
     *
     * @param UrlInterface $url
     * @param RequestInterface $request
     */
    public function __construct(
        UrlInterface             $url,
        private RequestInterface $request
    ) {
        $this->urlBuilder = $url;
    }

    /**
     * Returns the button data
     *
     * @return array
     */
    public function getButtonData(): array
    {
//        Goes back to the relevant event edit page
        $eventId = $this->request->getParam('event_id');
        $url = $this->urlBuilder->getUrl('*/event/edit/event_id/' . $eventId);

        return [
            'label' => __('Back'),
            'class' => 'back',
            'on_click' => sprintf("location.href = '%s';", $url),
        ];
    }
}
