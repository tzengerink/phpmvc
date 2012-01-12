<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * File helps with reading and writing files.
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class File {

	/**
	 * Create a new file instance.
	 *
	 * @param   string   path
	 * @param   integer  permissions
	 * @return  File
	 */
	public static function factory($path, $chmod = 0644)
	{
		return new File($path, $chmod);
	}

	/**
	 * @var  string  file path
	 */
	public $path;

	/**
	 * @var  integer  file permissions
	 */
	protected $_chmod;

	/**
	 * Construct a new file instance.
	 * Use the factory method instead.
	 *
	 * @param  string   path
	 * @param  integer  permissions
	 */
	public function __construct($path, $chmod)
	{
		$this->_chmod = $chmod;
		$this->path   = $path;

		// Create file if it does not exist
		if ( ! file_exists($this->path))
		{
			touch($this->path);
		}

		// Change file permissions
		chmod($this->path, $this->_chmod);
	}

	/**
	 * Append data to a file.
	 *
	 * @param   string   data
	 * @return  boolean  successful
	 */
	public function append($data = '')
	{
		$handle  = fopen($this->path, 'a');
		$success = fwrite($handle, $data);
		fclose($handle);

		return (bool) $success;
	}

	/**
	 * Delete a file from the filesystem.
	 *
	 * @return  boolean  successful
	 */
	public function delete()
	{
		return (unlink($this->path) AND ! file_exists($this->path));
	}

	/**
	 * Get modification date.
	 *
	 * @return  integer  timestamp
	 */
	public function mtime()
	{
		return filemtime($this->path);
	}

	/**
	 * Read the contents of a file.
	 *
	 * @return  string  contents
	 */
	public function read()
	{
		if ($filesize = filesize($this->path))
		{
			$handle = fopen($this->path, 'r');
			$data   = fread($handle, $filesize);
			fclose($handle);

			return $data;
		}
		
		return '';
	}
	
	/**
	 * Write data to a file.
	 *
	 * @param   string   data
	 * @return  boolean  successful
	 */
	public function write($data = '')
	{
		$handle  = fopen($this->path, 'w');
		$success = fwrite($handle, $data);
		fclose($handle);

		return (bool) $success;
	}

	/**
	 * Get last lines of a file.
	 *
	 * @param   integer  number of lines
	 * @return  string   last line(s) of file
	 */
	public function tail($lines = 1)
	{
		// Check if argument is integer
		if ( ! is_int($lines))
		{
			throw Exception('First parameter must be an integer');
		}

		// Return last X lines of file
		$file = escapeshellarg($this->path);
		return `tail -n $lines $file`;
	}

} // End File
