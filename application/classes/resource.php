<?php defined('DOCROOT') or die('No direct script access.');
/**
 * This class keeps track of all the JavaScript files and stylesheets added
 * by your views and scripts. It is better to put your scripts at the bottom 
 * of the page, instead of directly at the position you need them.
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class Resource {

	/**
	 * @var  array  scripts to render
	 */
	private static $_scripts = array();

	/**
	 * @var  array  rendered scripts
	 */
	private static $_rendered_scripts = array();

	/**
	 * @var  array  stylesheets to render
	 */
	private static $_stylesheets = array();

	/**
	 * @var  array  rendered stylesheets
	 */
	private static $_rendered_stylesheets = array();

	/**
	 * Add a script to the list of scripts.
	 *
	 * @param  mixed    (array of) file(s) to add to the list of scripts
	 * @param  boolean  render the scripts immediatly after adding 
	 * @param  boolean  prepend the file(s) instead of appending them
	 */
	public static function script($input = NULL, $render = FALSE, $prepend = FALSE)
	{
		if (is_array($input))
		{
			foreach ($input as $file)
			{
				if ( ! in_array($file, self::$_scripts) AND ! in_array($file, self::$_rendered_scripts))
				{
					if ($prepend)
					{
						array_unshift(self::$_scripts, self::_prepend_base($file));
					}
					else
					{
						self::$_scripts[] = self::_prepend_base($file);
					}
				}
			}
		}
		else
		{
			if ( ! in_array($input, self::$_scripts) AND ! in_array($input, self::$_rendered_scripts))
			{
				if ($prepend)
				{
					array_unshift(self::$_scripts, self::_prepend_base($input));
				}
				else
				{
					self::$_scripts[] = self::_prepend_base($input);
				}
			}
		}

		if ($render)
		{
			self::render("scripts");
		}
	}

	/**
	 * Add a style to the list of stylesheets.
	 *
	 * @param  mixed    (array of) file(s) to add to the list of stylesheets
	 * @param  boolean  render the stylesheets immediatly after adding
	 */
	public static function style($input = NULL, $render = FALSE)
	{
		if (is_array($input))
		{
			foreach ($input as $file)
			{
				if ( ! in_array($file, self::$_stylesheets) AND ! in_array($file, self::$_rendered_stylesheets))
				{
					self::$_stylesheets[] = self::_prepand_base($file);
				}
			}
		}
		else
		{
			if ( ! in_array($input, self::$_stylesheets) AND ! in_array($input, self::$_rendered_scripts))
			{
				self::$_stylesheets[] = self::_prepend_base($input);
			}
		}

		if ($render)
		{
			self::render("styles");
		}
	}

	/**
	 * Render the scripts, styles or both.
	 *
	 * @param   string  (scripts|styles)
	 * @return  void
	 */
	public static function render($type = "")
	{
		switch($type)
		{
			case "styles":
				self::_render_styles();
				break;

			case "scripts":
				self::_render_scripts();
				break;

			default:
				self::_render_styles();
				self::_render_scripts();
				break;
		}
	}

	/**
	 * Prepend the base url a file if it does not start with http.
	 *
	 * @param   string  file path
	 * @return  string  file url
	 */
	protected static function _prepend_base($file)
	{
		return strpos($file, 'http://') ? $file : BASEURL.$file;
	}

	/**
	 * Render scripts
	 *
	 * Loop the array with scripts, add a script tag to the DOM,
	 * add the script to the array of rendered scripts, empty 
	 * the scripts array
	 *
	 * @return  void
	 */
	protected static function _render_scripts()
	{
		foreach (self::$_scripts as $file)
		{
			echo "<script src=\"".$file."\" type=\"text/javascript\" charset=\"utf-8\"></script>\n";
			self::$_rendered_scripts[] = $file;
		}
		self::$_scripts = array();
	}

	/**
	 * Render stylesheets.
	 * 
	 * Loop the array with stylesheets, add a stylesheet tag to the DOM,
	 * add the stylesheet to the array of rendered stylesheets, empty 
	 * the stylesheets array
	 *
	 * @return  void
	 */
	protected static function _render_styles()
	{
		foreach (self::$_stylesheets as $file)
		{
			echo "<link href=\"".$file."\" type=\"text/css\" rel=\"stylesheet\" />\n";
			self::$_rendered_stylesheets[] = $file;
		}
		self::$_stylesheets = array();
	}

} // End Core Include
