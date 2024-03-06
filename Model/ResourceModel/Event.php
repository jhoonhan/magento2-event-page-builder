<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Event extends AbstractDb
{
    /** @var string Main table name */
    public const EVENT_TABLE = 'hanstudio_eventpagebuilder_event';

    /** @var string Main table primary key field name */
    public const ID_FIELD_NAME = 'event_id';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(self::EVENT_TABLE, self::ID_FIELD_NAME);
    }
}
