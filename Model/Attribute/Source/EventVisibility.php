<?php
declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Model\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class EventVisibility extends AbstractSource
{
    /**
     * Get all options
     *
     * @retrun array
     */
    public function getAllOptions(): array
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('Public'), 'value' => 'public'],
                ['label' => __('Builder'), 'value' => 'builder'],
                ['label' => __('Partner'), 'value' => 'partner'],
            ];
        }
        return $this->_options;
    }
}
