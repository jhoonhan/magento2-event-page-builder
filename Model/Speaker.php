<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Model;

use Magento\Framework\Model\AbstractModel;

class Speaker extends AbstractModel
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\Speaker::class);
    }
}
