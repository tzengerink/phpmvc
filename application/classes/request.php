<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Request checks the requested uri and executes the correct controller and action.
 *
 * Example usage:
 * <code>
 * Request::instance()->execute();
 * </code>
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class Request {

	/**
	 * @var  Request
	 */
	protected static $_instance;

	/**
	 * Get the Request instance. If no instance is set yet it will
	 * create a new instance.
	 *
	 * @return  Request
	 * @uses    Config
	 */
	public static function instance()
	{
		// Set instance if not set already OR when uri is different
		if ( ! Request::$_instance)
		{
			// Get uri string
			$uri = str_replace(Config::load('core')->get('webroot'), '', isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');

			// Set instance
			Request::$_instance = new Request($uri);
		}

		// Return the instance
		return Request::$_instance;
	}

	/**
	 * @var  string  uri
	 */
	public $uri;

	/**
	 * @var  string  client IP-address
	 */
	public $client_ip;

	/**
	 * @var  string  user agent
	 */
	public $user_agent;

	/** 
	 * The constructor method should not be used. Use the 
	 * instance method instead.
	 *
	 * @param  string  uri
	 * @uses   Router
	 */
	public function __construct($uri)
	{
		$this->client_ip  = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
		$this->router     = Router::factory($uri);
		$this->uri        = $uri;
		$this->user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	}

	/**
	 * Returns the requested parameter of FALSE if not set.
	 *
	 * @return  string  parameter name
	 * @uses    Router
	 */
	public function param($name)
	{
		return $this->router->get($name);
	}

	/**
	 * Execute the current request.
	 * 
	 * @uses  View::factory()
	 */
	public function execute()
	{
		// Get controller instance if it exists
		try
		{
			$class      = 'Controller_'.ucwords($this->param('controller'));
			$controller = new $class;
		}
		catch (Exception $e)
		{
			echo View::factory('error/404', array(), 'html')->render();
			exit;
		}

		// Execute requested action
		$action = 'action_'.$this->param('action');
		if ( ! method_exists($controller, $action))
		{
			echo View::factory('error/404', array(), 'html')->render();
			exit;
		}
		$controller->{$action}();
	}

	/**
	 * Redirect to another URL.
	 *
	 * @param  string   URL
	 * @parem  integer  status
	 */
	public function redirect($url, $code = 302)
	{
		Response::instance()
			->status($code)
			->header('Location', $url)
			->render();
	}

} // End Request
