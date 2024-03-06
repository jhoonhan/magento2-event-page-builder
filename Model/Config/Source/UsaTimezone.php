<?php

namespace HanStudio\EventPageBuilder\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * E009: Timezone source model
 * @api
 * @since 100.0.2
 */
class UsaTimezone implements ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 0, 'label' => 'AKT (Alaska)'],
            ['value' => 1, 'label' => 'PT (Pacific)'],
            ['value' => 2, 'label' => 'MT (Mountain)'],
            ['value' => 3, 'label' => 'CT (Central)'],
            ['value' => 4, 'label' => 'ET (Eastern)'],
            ['value' => 5, 'label' => 'AST (Atlantic)'],
            ['value' => 6, 'label' => 'HST (Hawaii)']
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            0 => 'America/Anchorage',
            1 => 'America/Los_Angeles',
            2 => 'America/Denver',
            3 => 'America/Chicago',
            4 => 'America/New_York',
            5 => 'America/Puerto_Rico',
            6 => 'Pacific/Honolulu'];
    }

    /**
     * Get timezone value
     *
     * @param int $value
     * @return string
     */
    public function getTimezoneValue(int $value): string
    {
        $timezoneArray = $this->toArray();
        return $timezoneArray[$value];
    }
}
