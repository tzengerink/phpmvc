<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Example SQL:
 * CREATE TABLE IF NOT EXISTS `users` (
 *   `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
 *   `username` VARCHAR(255) NOT NULL,
 *   `password` VARCHAR(255) NOT NULL,
 *   `email` VARCHAR(255) NOT NULL,
 *   `level` INT(11) DEFAULT 1,
 *   `nonce` VARCHAR(32) NOT NULL,
 *   `ip_address` VARCHAR(255) DEFAULT NULL,
 *   `logins` INT(11) NOT NULL DEFAULT 0,
 *   `last_login` INT(10) DEFAULT NULL,
 *   `created` INT(10) NOT NULL,
 *   `updated` INT(10) DEFAULT NULL,
 *   PRIMARY KEY (`id`)   
 * ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
 *
 * @package   PHPMVC
 * @category  Model
 * @author    T. Zengerink
 */
class Model_User extends Model {

	/**
	 * Array of regular expressions for validation.
	 *
	 * @return  array  regexes
	 */
	public function validation()
	{
		return array(
			'id'         => '/^\d{1,11}$/',
			'username'   => '/^\w{4,255}$/i',
			'email'      => '/^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/i',
			'level'      => '/^\d{1,11}$/',
			'nonce'      => '/^[a-z0-9]{32}$/i',
			'ip_address' => '/^(\d{1,3}\.){3}\d{1,3}$/',
			'logins'     => '/^\d{1,11}$/',
			'last_login' => '/^[0-9]{10}$/',
			'created'    => '/^[0-9]{10}$/',
			'updated'    => '/^[0-9]{10}$/',
		);
	}

	/**
	 * Insert a new user.
	 *
	 * @param   array    data
	 * @return  boolean  successfully inserted
	 * @uses    Auth
	 */
	public function insert(array $data = NULL)
	{
		// Set nonce if not set
		if ( ! array_key_exists('nonce', $data))
		{
			$data['nonce'] = Auth::instance()->nonce();
		}

		// Hash the password
		if (array_key_exists('password', $data))
		{
			$data['password'] = Auth::instance()->hash($data['password'], $data['nonce']);
		}

		// Set created if not set
		if ( ! array_key_exists('created', $data))
		{
			$data['created'] = time();
		}

		// Execute parent's insert method
		return parent::insert($data);
	}

	/**
	 * Update a user's database record.
	 *
	 * @param   array    data
	 * @param   array    where
	 * @return  boolean  updated successfully
	 */
	public function update(array $data = NULL, array $where = NULL)
	{
		// Get user database record
		$user = array_pop($this->where($where));

		// Hash the password
		if ( ! empty($user) AND array_key_exists('password', $data))
		{
			$data['password'] = Auth::instance()->hash($data['password'], $user['nonce']);
		}

		// Set updated
		if ( ! array_key_exists('updated', $data))
		{
			$data['updated'] = time();
		}

		// Execute parent's update method
		return parent::update($data, $where);
	}

	/**
	 * Register a user logging in.
	 *
	 * @param  integer  user id
	 * @param  boolean  registered successfully
	 * @uses   Request::instance();
	 */
	public function register_login($id)
	{
		// Get user data
		$user = $this->id($id);

		// Update user record
		return parent::update(array(
			'ip_address' => Request::instance()->client_ip,
			'logins'     => $user['logins'] + 1,
			'last_login' => time(),
		), array(
			'id' => $id,
		));
	}

} // End Model User
