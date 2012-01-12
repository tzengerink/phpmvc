<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Caching various variables to a file for later use.
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class Cache {

	/**
	 * @var  integer  lifetime
	 */
	public static $lifetime = 3600;

	/**
	 * Cache data under a given key.
	 *
	 * @param   string   key
	 * @param   mixed    data
	 * @return  boolean  successful
	 * @uses    File
	 */
	public static function set($key, $data)
	{
		return File::factory(APPROOT.'cache/'.md5($key).'.cache', 0777)
			->write(serialize($data));
	}

	/**
	 * Get the data belonging to a given cache key.
	 *
	 * @param   string  key
	 * @return  mixed   data
	 * @uses    File
	 */
	public static function get($key)
	{
		// Check if file exists
		if ( ! file_exists(APPROOT.'cache/'.md5($key).'.cache'))
		{
			return FALSE;
		}

		// Create file
		$file = File::factory(APPROOT.'cache/'.md5($key).'.cache', 0777);

		// Check if it exists and lifetime has not passed
		if ($file->mtime() + self::$lifetime >= time())
		{
			return unserialize($file->read());
		}
		else
		{
			return ! self::clear($key);
		}
	}

	/**
	 * Clear the cache for a given key.
	 *
	 * @param   string   key
	 * @return  boolean  removed successfully
	 * @uses    File
	 */
	public static function clear($key)
	{
		return File::factory(APPROOT.'cache/'.md5($key).'.cache', 0777)
			->delete();
	}

} // End Cache
