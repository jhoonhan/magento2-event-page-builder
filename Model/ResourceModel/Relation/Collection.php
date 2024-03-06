<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Model\ResourceModel\Relation;

use HanStudio\EventPageBuilder\Model\Relation;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use HanStudio\EventPageBuilder\Model\ResourceModel\Relation as RelationResourceModel;

class Collection extends AbstractCollection
{
    /**
     * Constructs the collection
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(Relation::class, RelationResourceModel::class);
    }
}
