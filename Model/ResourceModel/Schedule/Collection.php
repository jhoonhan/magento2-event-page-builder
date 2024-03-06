<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Model\ResourceModel\Schedule;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use HanStudio\EventPageBuilder\Model\Schedule;

class Collection extends AbstractCollection
{
    /**
     * Constructs the collection
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(Schedule::class, \HanStudio\EventPageBuilder\Model\ResourceModel\Schedule::class);
    }
}
