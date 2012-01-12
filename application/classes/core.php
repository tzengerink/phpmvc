<?php defined('DOCROOT') or die('No direct access allowed');
/**
 * Core provides some of the core helper functions like debugging, auto loading 
 * and exception handling.
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class Core {

	/**
	 * Method to automatically loads classes when they are requested.
	 *
	 * @param  string  class name
	 */
	public static function auto_load($class)
	{
		// Get file path based on class name
		$file = APPROOT.'classes/'.str_replace('_', '/', strtolower($class)).'.php';

		// Require the file or die
		if (file_exists($file))
		{
			require_once($file);
		}
		else
		{
			throw new Exception('Unable to auto load class: '.$class);
		}
	}

	/**
	 * Method for easy variable debugging.
	 *
	 * @return  string  debug string for all argumants given
	 */
	public static function debug()
	{
		$args = func_get_args();
		if ( ! empty($args))
		{
			$str = ''; 
			foreach ($args as $arg)
			{
				if (is_bool($arg))
				{
					$str .= ($arg ? '(bool) TRUE' : '(bool) FALSE').'<br /><br />';
				}
				else
				{
					$str .= '<pre>'.print_r($arg, TRUE).'</pre>';
				}
			}
			return $str;
		}
		return "No variable(s) provided.\n";
	}

	/**
	 * Method to handle all exceptions thown by the application.
	 *
	 * @param  Exception
	 * @uses   Config
	 * @uses   Log
	 * @uses   View
	 */
	public static function exception_handler(Exception $e)
	{
		// Add a message to the logs when on production environment
		if (Config::load('core')->get('is_production'))
		{
			$trace = $e->getTrace();
			Log::instance()->append($e->getMessage().' ~ '.$trace[0]['file'].' ['.$trace[0]['line'].']');
		}

		// Show a nice exception on production environment and a bit more 
		// information when in development
		$view = Config::load('core')->get('is_production') 
			? View::factory('error/500', array(), 'html')
			: View::factory('error/error');

		// Render the error view
		echo $view
			->set('code', $e->getCode())
			->set('message', $e->getMessage())
			->set('trace', $e->getTrace())
			->render();
	}

} // End Core
