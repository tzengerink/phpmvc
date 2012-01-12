<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Handles all cookie variables for you.
 *
 * Example:
 * <code>
 * Cookie::set('name', 'value');
 * Cookie::get('name');
 * Cookie::delete('name');
 * </code>
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class Cookie {

	/**
	 * @var   integer  expiration
	 */
	public static $expiration = 31556926;

	/**
	 * Gets a cookie variable.
	 *
	 * @param   string  variable name
	 * @return  mixed   variable value
	 * @uses    Config
	 * @uses    Encrypt
	 */
	public static function get($name)
	{
		return array_key_exists($name, $_COOKIE)
			? Encrypt::instance(Config::load('core')->get('secure_key'))->decrypt($_COOKIE[$name])
			: NULL;
	}

	/**
	 * Set a cookie variable.
	 *
	 * @param   string   variable name
	 * @param   mixed    variable value
	 * @return  boolean  successfully set
	 * @uses    Config
	 * @uses    Encrypt
	 */
	public static function set($name, $value)
	{
		$encrypted = Encrypt::instance(Config::load('core')->get('secure_key'))->encrypt($value);
		$expires   = time() + self::$expiration;

		return setcookie($name, $encrypted, $expires);
	}

	/**
	 * Delete a cookie variable.
	 *
	 * @param   string   variable name
	 * @return  boolean  successfully deleted
	 */
	public static function delete($name)
	{
		if (array_key_exists($name, $_COOKIE))
		{
			unset($_COOKIE[$name]);
		}

		return setcookie($name, NULL, -86400);
	}

} // End Cookie
