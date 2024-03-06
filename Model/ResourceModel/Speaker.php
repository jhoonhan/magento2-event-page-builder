<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Speaker extends AbstractDb
{
    /** @var string Main table name */
    public const SPEAKER_TABLE = 'hanstudio_eventpagebuilder_speaker';

    /** @var string Main table primary key field name */
    public const ID_FIELD_NAME = 'speaker_id';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(self::SPEAKER_TABLE, self::ID_FIELD_NAME);
    }
}
