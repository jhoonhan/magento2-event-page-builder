<?php

namespace HanStudio\EventPageBuilder\Model;

use HanStudio\EventPageBuilder\Api\Data\DataInterface;
use Magento\Framework\Model\AbstractModel;

class Data extends AbstractModel implements DataInterface
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\Event::class);
    }
}
