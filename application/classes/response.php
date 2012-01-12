<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Response creates the response with a valid HTTP header, status codes, status message
 * any other header and the body.
 *
 *	Example usage:
 * <code>
 * Response::instance()
 *   ->status(200)
 *   ->header('Content-Type', 'text/html')
 *   ->body('<h1>Hello, World!</h1>')
 *   ->render();
 * </code>
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class Response {

	/**
	 * @var  Response
	 */
	protected static $_instance;

	/**
	 * @var  array  messages
	 */
	protected static $_messages = array(
		// Informational 1xx
		100 => 'Continue',
		101 => 'Switching Protocols',
		// Success 2xx
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		// Redirection 3xx
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found', // 1.1
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		// 306 is deprecated but reserved
		307 => 'Temporary Redirect',
		// Client Error 4xx
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		// Server Error 5xx
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		509 => 'Bandwidth Limit Exceeded'
	);

	/**
	 * Get a Response instance. If no instance is set an instance
	 * will be created.
	 *
	 * @return  Response
	 */
	public static function instance()
	{
		if ( ! Response::$_instance)
		{
			Response::$_instance = new Response;
		}
				
		return Response::$_instance;
	}

	/**
	 * @var  string  body
	 */
	protected $_body = '';

	/**
	 * @var  array  headers
	 */
	protected $_headers = array(
		'Content-Type'  => 'text/html; charset=utf8',
		'Cache-Control' => 'no-cache; must-revalidate',
	);

	/**
	 * @var  integer  status
	 */
	protected $_status = 200;

	/**
	 * @param   string  body
	 * @return  Response
	 */
	public function body($string)
	{
		// Set body
		$this->_body = $string;

		// For chainability
		return $this;
	}

	/**
	 * @param   string  header key
	 * @param   string  header value
	 * @return  Response
	 */
	public function header($key, $value)
	{
		// Set header
		$this->_headers[$key] = $value;

		// For chainability
		return $this;
	}

	/**
	 * Render the current Response instance.
	 */
	public function render()
	{
		// Set protocol, status code, status message
		header('HTTP/1.1 '.$this->_status.' '.Response::$_messages[$this->_status]);

		// Set other headers
		foreach ($this->_headers as $key => $value)
		{
			header($key.': '.$value);
		}

		// Print the body
		echo (string) $this->_body;
		exit;
	}

	/**
	 * @param   integer  status code
	 * @return  Response
	 */
	public function status($code)
	{
		// Set status code
		$this->_status = $code;

		// For chainability
		return $this;
	}

} // End Response
