<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Block\Adminhtml\Event\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Save implements ButtonProviderInterface
{
    public $parentId = \Magento\Catalog\Model\Category::TREE_ROOT_ID;

    /**
     * Returns the button data
     *
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Save Event'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'button' => [
                        'event' => 'save',
                    ],
                ],
            ],
        ];
    }
}
