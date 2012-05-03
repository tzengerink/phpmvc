<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * I18n helps in translating short strings into the current language.
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class I18n {

	/**
	 * @var  string  language
	 */
	public static $lang = 'en';

	/**
	 * @var  array  cache of translations
	 */
	public static $cache = array();

	/**
	 * Get / set the current language.
	 *
	 * @param   string  language
	 * @return  string  language
	 */
	public static function lang($lang = NULL)
	{
		// Set the language
		if ($lang !== NULL)
		{
			self::$lang = $lang;
		}

		// Return current language
		return self::$lang;
	}

	/**
	 * Load a language file into the cache.
	 *
	 * @param   string  language
	 * @return  array   translations
	 */
	public static function load($lang = NULL)
	{
		// Set language
		if ($lang === NULL)
		{
			$lang = self::$lang;
		}

		// Set cache if it is not set already
		if ( ! array_key_exists($lang, self::$cache))
		{
			if ( ! file_exists(APPROOT.'i18n/'.$lang.EXT))
			{
				throw new Exception('Unable to load language file ('.$lang.')');
			}

			self::$cache[$lang] = require_once(APPROOT.'i18n/'.$lang.EXT);
		}

		// Return the translations array
		return self::$cache[$lang];
	}

	/**
	 * Translate the given key.
	 *
	 * @param   string  key
	 * @param   array   params
	 * @param   string  language
	 * @return  string  translation
	 */
	public static function translate($str, array $params = NULL, $lang = NULL)
	{
		// Set language
		if ($lang === NULL)
		{
			$lang = I18n::lang();
		}

		// Get translations array
		$array = self::load($lang);
		
		// Get the value for the requested key or use the key
		$translation = array_key_exists($str, $array) ? $array[$str] : $str;

		// Set the parameter if they are requested
		if (strpos($translation, ':'))
		{
			foreach ($params as $key => $value)
			{
				$translation = str_replace($key, $value, $translation);
			}
		}

		// Return the translation
		return $translation;
	}

} // End I18n

/**
 * Make the I18n::translate() function globally available as __(). It can be 
 * used to translate a certain string into the required language.
 *
 * @param   string  key
 * @param   string  language
 * @return  string  translated
 * @uses    I18n
 */
function __($str, $params = array(), $lang = NULL)
{
	return I18n::translate($str, $params, $lang);
}
