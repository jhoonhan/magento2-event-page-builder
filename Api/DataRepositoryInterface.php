<?php

namespace HanStudio\EventPageBuilder\Api;

use HanStudio\EventPageBuilder\Api\Data\DataInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Event schedule CRUD interface.
 *
 * @api
 * @since 1.0.0
 */
interface DataRepositoryInterface
{
    /**
     * Get event by ID.
     *
     * @param int $event_id
     * @return string
     * @throws LocalizedException
     */
    public function getById(int $event_id): string;

    /**
     * Insert new event.
     *
     * @param int $id
     * @return string
     * @throws LocalizedException
     */
    public function image(int $id): string;
}
