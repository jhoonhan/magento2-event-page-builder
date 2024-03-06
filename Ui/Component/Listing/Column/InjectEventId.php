<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Ui\Component\Listing\Column;

use Magento\Ui\Component\Action;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;

class InjectEventId extends Action
{

    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param array|null $actions
     */
    public function __construct(
        ContextInterface         $context,
        private RequestInterface $request,
        private UrlInterface     $urlBuilder,
        array                    $components = [],
        array                    $data = [],
        mixed                    $actions = null
    ) {
        parent::__construct($context, $components, $data, $actions);
    }

    /**
     * S009-3: Injects event_id into schedule/mass-delete action
     *
     * @return void
     */
    public function prepare(): void
    {
        parent::prepare();
        $config = $this->getConfiguration();
        $params = ['event_id' => $this->request->getParam('event_id')];
        $config['url'] = $this->urlBuilder->getUrl($config['urlPath'], $params);

        $this->setData('config', $config);
    }
}
