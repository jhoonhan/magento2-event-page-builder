<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Block\Adminhtml\Event\Edit\Button;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Back implements ButtonProviderInterface
{

    /**
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        public UrlInterface $urlBuilder
    ) {
    }

    /**
     * Returns the button data
     *
     * @return array
     */
    public function getButtonData(): array
    {
        $url = $this->urlBuilder->getUrl('*/*/');

        return [
            'label' => __('Back'),
            'class' => 'back',
            'on_click' => sprintf("location.href = '%s';", $url),
        ];
    }
}
