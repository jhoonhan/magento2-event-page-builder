const getTimezones = (index: number) => {
    const timezones: string[] = [
        'America/Anchorage', /* 0 */
        'America/Los_Angeles', /* 1 */
        'America/Denver', /* 2 */
        'America/Chicago', /* 3 */
        'America/New_York', /* 4 */
        'America/Puerto_Rico', /* 5 */
        'Pacific/Honolulu' /* 6 */
    ]

    return timezones[index];
};

export default getTimezones;
