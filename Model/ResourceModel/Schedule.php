<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Schedule extends AbstractDb
{
    /** @var string Main table name */
    public const SCHEDULE_TABLE = 'hanstudio_eventpagebuilder_schedule';

    /** @var string Main table primary key field name */
    public const ID_FIELD_NAME = 'schedule_id';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(self::SCHEDULE_TABLE, self::ID_FIELD_NAME);
    }
}
