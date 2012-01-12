<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Log errors to a log file so they can be reviewed at any point in the future.
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class Log {

	/**
	 * @var  Log  instance
	 */
	private static $instance;

	/**
	 * Get the instance, set a new instance if it does not exist.
	 *
	 * @return  Log
	 */
	public static function instance()
	{
		if ( ! self::$instance)
		{
			self::$instance = new Log;
		}

		return self::$instance;
	}

	/**
	 * @var  string  directory
	 */
	protected $_dir;

	/**
	 * @var  string  file
	 */
	protected $_file;

	/**
	 * Constructor.
	 * Use the instance method instead.
	 *
	 * @uses  File
	 */
	public function __construct()
	{
		// Get date variables
		$d = date('d');
		$m = date('m');
		$y = date('Y');

		// Set properties
		$this->_dir  = APPROOT.'log/';
		$this->_file = $this->_dir.$y.'/'.$m.'/'.$d.'.log';

		// Check log directory
		if ( ! is_writable($this->_dir))
		{
			throw new Exception('Unable to write to log directory');
		}
		
		// Create year dir
		if ( ! is_dir($this->_dir.$y))
		{
			mkdir($this->_dir.$y, 02777);
			chmod($this->_dir.$y, 02777);
		}

		// Create month dir
		if ( ! is_dir($this->_dir.$y.'/'.$m))
		{
			mkdir($this->_dir.$y.'/'.$m, 02777);
			chmod($this->_dir.$y.'/'.$m, 02777);
		}
		
		// Set file to File instance
		$this->_file = File::factory($this->_file);
	}

	/**
	 * Append a line to the logfile.
	 *
	 * @param   string   message
	 * @param   string   type
	 * @return  boolean  successful
	 * @uses    Date
	 */
	public function append($message, $type = 'Exception')
	{
		$this->_file->append(Date::factory()->format('Y/m/d H:i:s').' *** '.$type.': '.trim($message)."\n");
	}

} // End Log
