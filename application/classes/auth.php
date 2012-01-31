<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Class for authentication of users.
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class Auth {

	/**
	 * @var  Auth  instance
	 */
	protected static $_instance;

	/**
	 * Get the Auth instance. If no Auth instance is set, a new instance 
	 * will be created.
	 *
	 * @return  Auth  instance
	 */
	public static function instance()
	{
		// Create instance if not already set
		if ( ! self::$_instance)
		{
			self::$_instance = new Auth;
		}

		// Return instance
		return self::$_instance;
	}

	/**
	 * @var  string  cookie variable name
	 */
	protected $_var_name = 'auth';

	/**
	 * Login a user.
	 *
	 * @param   string   username
	 * @param   string   password
	 * @return  boolean  login is correct
	 * @uses    Config
	 * @uses    Cookie
	 * @uses    Model
	 * @uses    Session
	 * @uses    User_Model
	 */
	public function login($username, $password, $remember = FALSE)
	{
		// Set Cookie / Session
		if ($remember)
		{
			Cookie::set($this->_var_name, $username.Config::load('core')->get('secure_key').$password);
		}
		else
		{
			Session::instance('auth')
				->set($this->_var_name, $username.Config::load('core')->get('secure_key').$password);
		}

		// Return if login was successful
		if ($this->logged_in($username, $password))
		{
			// Register user login
			$model = Model::factory('user')->where(array('username' => $username));
			$user  = array_pop($model);
			Model::factory('user')->register_login($user['id']);

			// Return login was successfull
			return TRUE;	
		}

		return FALSE;
	}

	/**
	 * Logout a user.
	 *
	 * @return  boolean  successfully logged out.
	 * @uses    Cookie
	 * @uses    Session
	 */
	public function logout()
	{
		// Delete the cookie and session
		return Cookie::delete($this->_var_name)AND Session::instance('auth')->delete($this->_var_name);
	}

	/**
	 * Check if a user is logged in. When no username and/or password are given get 
	 * them from the Cookie or Session.
	 *
	 * @param   string   username
	 * @param   string   password
	 * @return  boolean  login is correct
	 * @uses    Cookie
	 * @uses    Config
	 * @uses    Session
	 */
	public function logged_in($username = FALSE, $password = FALSE)
	{
		// Get variable from Session / Cookie
		$variable = Session::instance('auth')->get($this->_var_name) 
			? Session::instance('auth')->get($this->_var_name) 
			: Cookie::get($this->_var_name);
		
		// Get username and password
		if ( ! $username OR ! $password)
		{
			list($username, $password) = explode(Config::load('core')->get('secure_key'), $variable);
		}

		// Return if the username and password are correct
		return (bool) $this->_check($username, $password);
	}

	/**
	 * Check if a user has a certain login level.
	 * 
	 * @param   integer  login level
	 * @return  boolean  user has the requested level
	 */
	public function is_level($level = 1)
	{
		// Get current user
		$user = $this->user();

		// Return if user level is high enough
		return ($user AND $user['level'] >= $level);
	}

	/**
	 * Return array containing user information.
	 *
	 * @return  mixed  user info or FALSE if not logged in
	 * @uses    Config
	 */
	public function user()
	{
		// Get variable from Session / Cookie
		$variable = Session::instance('auth')->get($this->_var_name) 
			? Session::instance('auth')->get($this->_var_name) 
			: Cookie::get($this->_var_name);
		
		if (empty($variable))
		{
			return FALSE;
		}

		// Explode cookie value into username and password (if possible)
		$explode = explode(Config::load('core')->get('secure_key'), $variable);
		list($username, $password) = (count($explode) == 2) ? $explode : array('', '');

		// Return user info or FALSE if not logged in
		return $this->_check($username, $password);
	}

	/**
	 * Hash a string.
	 * Primarily used for hashing passwords.
	 *
	 * @param   string  to be hashed
	 * @param   string  nonce to use when hashing
	 * @return  string  hashed string
	 * @uses    Config
	 */
	public function hash($string, $nonce)
	{
		return hash_hmac('sha256', $string.$nonce, Config::load('core')->get('secure_key'));
	}

	/**
	 * Generate a nonce string of a certain length.
	 *
	 * @param   integer  length of the random string
	 * @return  string   random string
	 */
	public function nonce($length = 32)
	{
		$pool = str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 1);
		$max  = count($pool) - 1;
		$str  = '';

		// Create a string of the required length using the pool's characters
		for ($i = 0; $i < $length; $i++)
		{
			$str .= $pool[mt_rand(0, $max)];
		}

		return $str;
	}

	/**
	 * Check if the user info is correct.
	 *
	 * @param   string  username
	 * @param   string  password
	 * @return  mixed   user info or FALSE if incorrect
	 * @uses    Model
	 */
	protected function _check($username, $password)
	{
		$model = Model::factory('user')->where(array('username' => $username));
		$user  = array_pop($model);

		// Compare password to the hashed version in the database
		if ( ! empty($user) AND ($this->hash($password, $user['nonce']) === $user['password']))
		{
			return $user;
		}

		return FALSE;
	}

} // End Auth
