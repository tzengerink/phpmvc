<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Date helps with all sorts of date functionality.
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class Date {

	/**
	 * @var  integer  year
	 */
	public static $year = 31556926;

	/**
	 * @var  integer  month
	 */
	public static $month = 2629744;

	/**
	 * @var  integer  week
	 */
	public static $week = 604800;

	/**
	 * @var  integer  day
	 */
	public static $day = 86400;

	/**
	 * @var  integer  hour
	 */
	public static $hour = 3600;

	/**
	 * @var  integer  minute
	 */
	public static $minute = 60;

	/**
	 * Get an array of seconds.
	 *
	 * @param   integer  first seconds
	 * @param   integer  last seconds
	 * @return  array    seconds
	 */
	public static function seconds($first = 0, $last = 59, $step = 5)
	{
		return self::_array($first, $last, $step, 2);
	}

	/**
	 * Get an array of minutes.
	 *
	 * @param   integer  first minute
	 * @param   integer  last minute
	 * @return  array    minutes
	 */
	public static function minutes($first = 0, $last = 59, $step = 5)
	{
		return self::_array($first, $last, $step, 2);
	}

	/**
	 * Get an array of hours.
	 *
	 * @param   integer  first hour
	 * @param   integer  last hour
	 * @return  array    hours
	 */
	public static function hours($first = 1, $last = 24, $step = 1)
	{
		return self::_array($first, $last, $step, 2);
	}

	/**
	 * Get an array of days.
	 * 
	 * @param   integer  first day
	 * @param   integer  last day
	 * @return  array    days
	 */
	public static function days($first = 1, $last = 31)
	{
		return self::_array($first, $last, 1, 2);
	}

	/**
	 * Get an array of months.
	 * 
	 * @param   integer  first month
	 * @param   integer  last month
	 * @return  array    months
	 */
	public static function months($first = 1, $last = 12)
	{
		return self::_array($first, $last, 1, 2);
	}

	/**
	 * Get an array of years.
	 * 
	 * @param   integer  first year
	 * @param   integer  last year
	 * @return  array    years
	 */
	public static function years($first = NULL, $last = NULL)
	{
		// By default -5 / +5 years
		$first = $first ? $first : (date('Y') - 5);
		$last  = $last ? $last : (date('Y') + 5);

		// Return list
		return self::_array($first, $last, 1);
	}

	/**
	 * Get an array of integers.
	 *
	 * @param   integer  first
	 * @param   integer  last
	 * @param   integer  steps
	 * @param   integer  minumum char length
	 * @return  array    list
	 */
	protected static function _array($first = 0, $last = 10, $step = 1, $min = 2)
	{
		// Set variables
		$first = $first < $last  ? $first : $last;
		$last  = $last  > $first ? $last  : $first;
		$min   = $min   > 1      ? $min   : 1;
		$step  = $step  > 1      ? $step  : 1;

		// Create the list
		$list = array();
		for ($i = $first; $i <= $last; $i += $step)
		{
			$list[$i] = sprintf('%02d', $i);
		}

		// Return created list
		return $list;
	}

	/**
	 * Create a new instance of Date.
	 *
	 * @param   integer  timestamp
	 * @return  Date
	 */
	public static function factory($timestamp = NULL)
	{
		return new Date($timestamp);
	}

	/**
	 * @var  string  format
	 */
	public $format = 'M d, Y H:i:s';

	/**
	 * @var  integer  timestamp
	 */
	public $timestamp;

	/**
	 * @var  string  timezone
	 */
	public $timezone;

	/**
	 * Construct a new Date instance. Use the factory method instead.
	 * The timestamp will be set to the current time of no timestamp is provided.
	 *
	 * @param  integer  timestamp
	 */
	public function __construct($timestamp = NULL)
	{
		$this->timestamp = $timestamp !== NULL ? $timestamp : time();
		$this->timezone  = date_default_timezone_get();
	}

	/**
	 * Get the date as a string with a certain format.
	 * The default format is used when no format given.
	 *
	 * @param  string  format
	 * @param  string  date
	 */
	public function format($format = '')
	{
		return $format ? date($format, $this->timestamp) : date($this->format, $this->timestamp);
	}

	/**
	 * Get the number of seconds between date and given timestamp.
	 *
	 * @param  integer  timestamp
	 */
	public function span($timestamp = NULL)
	{
		// Set variable defaults
		$timestamp = is_int($timestamp) ? $timestamp : time();
		$span      = array();
		$diff      = $timestamp < $this->timestamp ? $this->timestamp - $timestamp : $timestamp - $this->timestamp;

		// Calculate..
		$diff -= Date::$year   * ($span['years']   = (int) floor($diff / Date::$year));
		$diff -= Date::$month  * ($span['months']  = (int) floor($diff / Date::$month));
		$diff -= Date::$week   * ($span['weeks']   = (int) floor($diff / Date::$week));
		$diff -= Date::$day    * ($span['days']    = (int) floor($diff / Date::$day));
		$diff -= Date::$hour   * ($span['hours']   = (int) floor($diff / Date::$hour));
		$diff -= Date::$minute * ($span['minutes'] = (int) floor($diff / Date::$minute));
		$span['seconds'] = $diff;

		// .. and return
		return $span;
	}

	/**
	 * Get a fuzzy time span between date and a given timestamp.
	 *
	 * @param  integer  timestamp
	 * @uses   I18n
	 */
	public function fuzzy_span($timestamp = NULL)
	{
		$timestamp = is_int($timestamp) ? $timestamp : time();
		$in_past   = $timestamp < $this->timestamp;
		$diff      = $in_past ? $this->timestamp - $timestamp : $timestamp - $this->timestamp;

		if ($diff < 10            AND $in_past)   return __('-seconds');
		if ($diff < 10            AND ! $in_past) return __('+seconds');
		if ($diff < Date::$minute AND $in_past)   return __('-minute');
		if ($diff < Date::$minute AND ! $in_past) return __('+minute');
		if ($diff < Date::$hour   AND $in_past)   return __('-hour');
		if ($diff < Date::$hour   AND ! $in_past) return __('+hour');
		if ($diff < Date::$day    AND $in_past)   return __('-day');
		if ($diff < Date::$day    AND ! $in_past) return __('+day');
		if ($diff < Date::$week   AND $in_past)   return __('-week');
		if ($diff < Date::$week   AND ! $in_past) return __('+week');
		if ($diff < Date::$month  AND $in_past)   return __('-month');
		if ($diff < Date::$month  AND ! $in_past) return __('+month');
		if ($diff < Date::$year   AND $in_past)   return __('-year');
		if ($diff < Date::$year   AND ! $in_past) return __('+year');
		if ($diff > Date::$year   AND $in_past)   return __('-+year');
		if ($diff > Date::$year   AND ! $in_past) return __('++year');
	}
		
} // End Date
