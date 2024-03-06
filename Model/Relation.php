<?php

namespace HanStudio\EventPageBuilder\Model;

use Magento\Framework\Model\AbstractModel;

class Relation extends AbstractModel
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\Relation::class);
    }
}
