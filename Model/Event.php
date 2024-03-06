<?php

namespace HanStudio\EventPageBuilder\Model;

use Magento\Framework\Model\AbstractModel;

class Event extends AbstractModel
{
    /**
     * Event constructor.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\Event::class);
    }
}
