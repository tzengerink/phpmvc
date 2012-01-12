<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Encrypt support the encryption and decryption of strings.
 *
 * Example:
 * <code>
 * $encrypted = Encrypt::instance('key')
 *   ->encrypt('string');
 * $decrypted = Encrypt::instance('key')
 *   ->decrypt('string');
 * </code>
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class Encrypt {

	/**
	 * @var  array  Encrypt instances
	 */
	protected static $_instances;

	/**
	 * Get an Encrypt instance. If no Encrypt instance is found a new
	 * one will be created.
	 *
	 * @param  string  encryption key
	 */
	public static function instance($key)
	{
		// Set instance if it does not exist
		if ( ! self::$_instances OR ! self::$_instances[$key])
		{
			self::$_instances[$key] = new Encrypt($key);
		}

		// Return the instance
		return self::$_instances[$key];
	}

	/**
	 * @var  string  encryption key
	 */
	protected static $_key;

	/**
	 * Construct a new encryption instance. Use the instance methods instead
	 * of this method.
	 *
	 * @param  string  encryption key
	 */
	public function __construct($key)
	{
		self::$_key = $key;
	}

	/**
	 * Encrypt a string.
	 *
	 * @param   string  to be encrypted
	 * @return  string  encrypted
	 */
	public function encrypt($str)
	{
		// Encode if mcrypt is loaded
		if (extension_loaded('mcrypt'))
		{
			$str = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(self::$_key), $str, MCRYPT_MODE_CBC, md5(md5(self::$_key)));
		}

		// Return URL safe encoded string
		return self::url_safe_base64encode($str);
	}

	/**
	 * Decrypt a string.
	 *
	 * @param   string  to be decrypted
	 * @return  string  decrypted
	 */
	public static function decrypt($str)
	{
		// URL safe decode
		$str = self::url_safe_base64decode($str);

		// Decode if mcrypt is loaded
		if (extension_loaded('mcrypt'))
		{
			$str = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(self::$_key), $str, MCRYPT_MODE_CBC, md5(md5(self::$_key))), "\0");
		}

		// Return the string
		return $str;
	}

	/**
	 * URL safe Base64 encoding.
	 *
	 * @param   string  to be encoded
	 * @return  string  encoded
	 */
	public static function url_safe_base64encode($str)
	{
		return strtr(base64_encode($str), '+/=', '-_~');
	}

	/**
	 * URL safe Base64 decoding.
	 *
	 * @param   string  to be decoded
	 * @return  string  decoded
	 */
	public static function url_safe_base64decode($str)
	{
		return base64_decode(strtr($str, '-_~', '+/='));
	}

} // End Encrypt
