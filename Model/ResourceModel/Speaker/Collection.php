<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Model\ResourceModel\Speaker;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use HanStudio\EventPageBuilder\Model\Speaker;

class Collection extends AbstractCollection
{
    /**
     * Constructs the collection
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(Speaker::class, \HanStudio\EventPageBuilder\Model\ResourceModel\Speaker::class);
    }
}
