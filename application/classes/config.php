<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Class for loading configuration files.
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class Config {

	/**
	 * @var  array  instances
	 */
	protected static $_instances = array();

	/**
	 * Load a specific configuration file.
	 *
	 * @param   string  name
	 * @return  array   configuration
	 */
	public static function load($name = 'core')
	{
		// If not yet set, create an instance of the configuration
		if ( ! isset(self::$_instances[$name]))
		{
			self::$_instances[$name] = new Config($name);
		}

		// Return the correct instance
		return self::$_instances[$name];
	}

	/**
	 * @var  string  file
	 */
	public $file;

	/**
	 * @var  array  config
	 */
	public $config = array();

	/**
	 * Constructor.
	 * Use the load method instead.
	 * 
	 * @param   string  name
	 * @throws  Exception
	 */
	public function __construct($name)
	{
		// Set file
		$this->file = APPROOT.'config/'.$name.'.php';

		// Check if file exists
		if ( ! file_exists($this->file))
		{
			throw new Exception('Configuration file \''.$this->file.'\' not found.');
		}

		// Require the file and store it in the config variable
		$this->config = require_once($this->file);
	}

	/**
	 * Get the value of a config key.
	 *
	 * @param   string  key
	 * @return  mixed   value
	 * @throws  Exception
	 */
	public function get($key)
	{
		// Check if key exists in config array
		if ( ! array_key_exists($key, $this->config))
		{
			throw new Exception('Config item \''.$key.'\' not found in \''.$this->file.'\'');
		}

		// Return the value
		return $this->config[$key];
	}

} // End Config
