<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Handles all session variables for you.
 *
 * Example:
 * <code>
 * Session::instance('session_name')
 *   ->set('var_name', 'var_value');
 * Session::instance('session_name')
 *   ->get('var_name');
 * Session::instance('session_name')
 *   ->delete('var_name');
 * </code>
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class Session {

	/**
	 * @var  Session  instances
	 */
	protected static $_instances;

	/**
	 * Get the instance. An instance will be created if it does not exist.
	 *
	 * @param   string   name of the instance
	 * @return  Session  instance
	 */
	public static function instance($name = 'default')
	{
		// Set instance if it does not exists
		if ( ! self::$_instances OR ! self::$_instances[$name])
		{
			self::$_instances[$name] = new Session($name);
		}

		// Return the instance
		return self::$_instances[$name];
	}

	/**
	 * @var  string  name of the instance
	 */
	protected $_name;

	/**
	 * Constructs a new instance. Use the instance method instead.
	 *
	 * @param   string   name of the instance
	 * @return  Session  instance
	 */
	public function __construct($name)
	{
		// Set variables
		$this->_name = $name;

		// Start the session
		session_start();

		// Fill with empty array if not exists
		if ( ! array_key_exists($name, $_SESSION))
		{
			$_SESSION[$name] = array();
		}
	}

	/**
	 * Gets a session variable
	 *
	 * @param   string  variable name
	 * @return  mixed   variable value
	 * @uses    Config
	 * @uses    Encrypt
	 */
	public function get($name)
	{
		return array_key_exists($name, $_SESSION[$this->_name])
			? Encrypt::instance(Config::load('core')->get('secure_key'))->decrypt($_SESSION[$this->_name][$name])
			: NULL;
	}

	/**
	 * Set a session variable
	 *
	 * @param   string  variable name
	 * @param   mixed   variable value
	 * @return  mixed   variable value
	 * @uses    Config
	 * @uses    Encrypt
	 */
	public function set($name, $value)
	{
		// Set session variable
		$_SESSION[$this->_name][$name] = Encrypt::instance(Config::load('core')->get('secure_key'))->encrypt($value);

		// Return the variable
		return $_SESSION[$this->_name][$name];
	}

	/**
	 * Delete a session variable
	 *
	 * @param   string  variable name
	 * @return  void
	 */
	public function delete($name)
	{
		// Delete the session variable
		if (array_key_exists($name, $_SESSION[$this->_name]))
		{
			unset($_SESSION[$this->_name][$name]);

			if (empty($_SESSION[$this->_name]))
			{
				unset($_SESSION[$this->_name]);
			}
		}
	}

} // End Session
