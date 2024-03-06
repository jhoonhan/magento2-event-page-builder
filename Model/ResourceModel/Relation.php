<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Relation extends AbstractDb
{
    /** @var string Main table name */
    public const RELATION_TABLE = 'hanstudio_eventpagebuilder_schspk';

    /** @var string Main table primary key field name */
    public const ID_FIELD_NAME = 'relation_id';

    /**
     * Constructs the collection
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(self::RELATION_TABLE, self::ID_FIELD_NAME);
    }
}
