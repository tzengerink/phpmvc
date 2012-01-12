<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Route checks the requested uri and returns the correct controller and action.
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class Router {

	/**
	 * @var  array  routes
	 */
	public static $routes = array();

	/**
	 * Create a new router.
	 *
	 * @param   string  uri
	 * @return  Router
	 */
	public static function factory($uri = '')
	{
		if (empty(self::$routes))
		{
			self::$routes = require_once(DOCROOT.'routes.php');
		}

		return new Router($uri);
	}

	/**
	 * @var  string  default action
	 */
	public $default_action = 'index';

	/**
	 * @var  string  default controller
	 */
	public $default_controller = 'index';

	/**
	 * @var  mixed  route
	 */
	public $route;

	/**
	 * @var  string  uri
	 */
	public $uri;

	/**
	 * The constructor method should not be used. Use the factory
	 * method instead.
	 *
	 * @param  string  uri
	 */
	public function __construct($uri)
	{
		$this->uri = $uri;
	}

	/**
	 * Get a part of the route.
	 *
	 * @param   string  key
	 * @return  mixed   value (FALSE of not set)
	 */
	public function get($key)
	{
		$route = $this->route();

		return array_key_exists($key, $route) ? $route[$key] : FALSE;
	}

	/**
	 * Route the uri using the routes.
	 *
	 * @return  mixed  routing info or FALSE if not found
	 */
	public function route()
	{
		if (isset($this->route))
		{
			// Return route if already set
			return $this->route;
		}
		else
		{
			// Set default route
			$route = array(
				'controller' => $this->default_controller,
				'action'     => $this->default_action,
			);

			// Check if there is a route to match the uri string
			foreach (self::$routes as $regex => $data)
			{
				if (preg_match($this->_prepare($regex), $this->uri, $matches))
				{
					// Replace any $# sign with the match with the same number
					foreach ($data as $key => $value)
					{
						if (strpos($value, '$') === 0 AND array_key_exists(substr($data[$key], 1), $matches))
						{
							$route[$key] = $matches[substr($data[$key], 1)];
						}
						else if (array_key_exists($key, $data))
						{
							$route[$key] = $value;	
						}

						// Set action to default action if not matching action found
						if ( ! array_key_exists(substr($data[$key], 1), $matches) AND $key === 'action')
						{
							$route[$key] = $this->default_action;
						}
					}

					// Once a match is found stop looking any further
					break;
				}
			}

			// Set route for future use
			$this->route = $route;

			return $route;
		}
	}

	/**
	 * Prepare a route expression for regular expression matching.
	 * Regular expressions used by the Router are always case-insensitive and 
	 * #-sign is used to bind the regular expression, so no need to escape 
	 * /-signs. Round brackets can be used for optional arguments, if not 
	 * provided the default controller / action will be called.
	 *
	 * @param   string  route expression
	 * @return  string  regular expression
	 */
	protected function _prepare($str)
	{
		// String replace brackets
		if (strpos($str, '{'))
		{
			$str = str_replace(array('{', '}'), array('(?:', ')?'), $str);
		}

		// Replace known words
		$str = str_replace(':controller', '([_a-z][_a-z0-9]*)', $str);
		$str = str_replace(':action',     '([_a-z][_a-z0-9]*)', $str);
		$str = str_replace(':id',         '([0-9]+)',           $str);
		$str = str_replace(':extension',  '(\.[a-z0-9]{2,4})',  $str);

		// Return as case-insensitive regular expression
		return '#^'.$str.'$#i';
	}

} // End Route
