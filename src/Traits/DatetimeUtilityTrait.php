<?php namespace Wc1c\Main\Traits;

defined('ABSPATH') || exit;

/**
 * DatetimeUtilityTrait
 *
 * @package Wc1c\Main\Traits
 */
trait DatetimeUtilityTrait
{
    /**
     * Convert MySQL datetime to PHP timestamp, forcing UTC.
     * Wrapper for strtotime with explicit UTC handling, without global timezone changes.
     *
     * @param string|null $time_string Time string to parse (e.g., '2026-07-24 12:00:00').If null, returns 0.
     * @param int|null $from_timestamp Optional Unix timestamp to use as base for relative parsing.
     *
     * @return int Unix timestamp, or 0 if the string could not be parsed or is null.
     */
    public function utilityStringToTimestamp( ?string $time_string, ?int $from_timestamp = null ): int
    {
        // Handle null input exactly as strtotime would — return 0 (false converted to int).
        if( null === $time_string )
        {
            return 0;
        }

        try
        {
            // Create a base DateTime object in UTC.
            if( null !== $from_timestamp )
            {
                $date = new \DateTime( '@' . $from_timestamp );
                $date->setTimezone( new \DateTimeZone( 'UTC' ) );
            }
            else
            {
                $date = new \DateTime( 'now', new \DateTimeZone( 'UTC' ) );
            }

            // Apply the modification (supports the same formats as strtotime).
            $modified = $date->modify($time_string);
            if( false === $modified ) {
                return 0;
            }

            return $date->getTimestamp();
        }
        catch(\Exception $e)
        {
            // Any parsing error returns 0, just like strtotime returning false.
            return 0;
        }
    }

	/**
	 * Helper to retrieve the timezone string for a site until
	 *
	 * @return string PHP timezone string for the site
	 */
	public function utilityTimezoneString(): string
	{
		// If site timezone string exists, return it
		$timezone = get_option('timezone_string');

		if($timezone)
		{
			return $timezone;
		}

		// Get UTC offset, if it isn't set then return UTC
		$utc_offset = (int) get_option('gmt_offset', 0);
		if(0 === $utc_offset)
		{
			return 'UTC';
		}

		// Adjust UTC offset from hours to seconds
		$utc_offset *= 3600;

		// Attempt to guess the timezone string from the UTC offset
		$timezone = timezone_name_from_abbr('', $utc_offset);
		if($timezone)
		{
			return $timezone;
		}

		// Last try, guess timezone string manually
		foreach(timezone_abbreviations_list() as $abbr)
		{
			foreach($abbr as $city)
			{
				// WordPress restrict the use of date(), since it's affected by timezone settings, but in this case is just what we need to guess the correct timezone
                // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
                if((bool) date('I') === (bool) $city['dst'] && $city['timezone_id'] && (int) $city['offset'] === $utc_offset)
				{
					return $city['timezone_id'];
				}
			}
		}

		return 'UTC';
	}

	/**
	 * Get timezone offset in seconds
	 *
	 * @return float
	 */
	public function utilityTimezoneOffset()
	{
		$timezone = get_option('timezone_string');

		if($timezone)
		{
			return (new \DateTimeZone($timezone))->getOffset(new \DateTime('now'));
		}

		return (float) get_option('gmt_offset', 0) * HOUR_IN_SECONDS;
	}

	/**
	 * @param $date
	 *
	 * @return string
	 */
	public function utilityPrettyDate($date): string
	{
		if(!$date)
		{
			return esc_html__('not', 'wc1c-maincore');
		}

		$timestamp_create = $this->utilityStringToTimestamp($date) + $this->utilityTimezoneOffset();

        return sprintf
		(
            /* translators: 1: Time create d/m/Y, 2: Time create H:i:s. */
            wp_kses_post('%1$s <span class="time">in: %2$s</span>'),
			date_i18n('d/m/Y', $timestamp_create),
			date_i18n('H:i:s', $timestamp_create)
		);
	}
}