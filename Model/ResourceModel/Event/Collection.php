<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Model\ResourceModel\Event;

use HanStudio\EventPageBuilder\Model\Event;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use HanStudio\EventPageBuilder\Model\ResourceModel\Event as EventResourceModel;

class Collection extends AbstractCollection
{
    /**
     * Constructs the collection
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(Event::class, EventResourceModel::class);
    }
}
