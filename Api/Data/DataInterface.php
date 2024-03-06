<?php

namespace HanStudio\EventPageBuilder\Api\Data;

/**
 * Blog post interface.
 *
 * @api
 * @since 1.0.0
 */
interface DataInterface
{
    public const EVENT_ID = 'event_id';
    public const NAME = 'name';
    public const EVENT_START_DATE = 'event_start_date';
    public const DESCRIPTION = 'description';
    public const IS_PUBLISHED = 'is_published';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const URL = 'url';
    public const SCHEDULES = 'schedules';
}
