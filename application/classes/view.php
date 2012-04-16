<?php defined('DOCROOT') or die('No direct access allowed.');
/** 
 * View loads a view file and returns renders the variable with the given data and returns
 * it as a string.
 *
 * Example:
 * View::factory('view/file', array('variable' => 'value'))
 *   ->set('variable', 'value')
 *   ->render();
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class View {

	/**
	 * Method to create a new view.
	 *
	 * @param   string  view file
	 * @param   array   data
	 * @param   string  file extension
	 * @return  View
	 */
	public static function factory($file, array $data = NULL, $ext = 'php')
	{
		return new View($file, $data, $ext);
	}

	/**
	 * @var  array  data
	 */
	protected $_data = array();

	/**
	 * @var  string  full file path
	 */
	protected $_file;

	/**
	 * @var  boolean  render
	 */
	protected $_render = FALSE;

	/**
	 * The constructor method should not be used. Use the
	 * factory function instead.
	 *
	 * @param  string  view file
	 * @param  array   data
	 * @param  string  file extension
	 */
	public function __construct($file, array $data = NULL, $ext = 'php')
	{
		$this->_data = $data !== NULL ? $data : array();
		$this->_file = DOCROOT.'views/'.$file.'.'.$ext;
	}

	/**
	 * @param   string  key
	 * @param   mixed   value
	 * @return  View
	 */
	public function set($key, $value)
	{
		// Set key => value
		$this->_data[$key] = $value;

		// For chainability
		return $this;
	}

	/**
	 * @return  array  data
	 */
	public function data()
	{
		return $this->_data;
	}

	/**
	 * Render the view using the given data.
	 */
	public function render()
	{
		if (file_exists($this->_file))
		{
			// Extract data
			extract($this->_data, EXTR_SKIP);

			// Start output buffer
			ob_start();	

			try
			{
				include $this->_file;
			}
			catch (Exception $e)
			{
				ob_end_clean();

				throw $e;
			}

			// Return the output
			return ob_get_clean();
		}
		else
		{
			throw new Exception('Unable to load the requested view');
		}
	}

} // End View
