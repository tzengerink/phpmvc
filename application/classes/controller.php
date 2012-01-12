<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Controller handles more specific requests.
 *
 * @package   PHPMVC
 * @category  Controller
 * @author    T. Zengerink
 */
class Controller {

	/**
	 * @var  integer  minimum level
	 */
	public $minimum_level = 0;

	/**
	 * @var  mixed  template name
	 */
	public $template = NULL;

	/**
	 * @var  mixed  user data
	 */
	public $user = FALSE;

	/**
	 * Constructor
	 *
	 * @uses  Auth
	 * @uses  Request
	 */
	public function __construct()
	{
		// Set variables
		$this->user = Auth::instance()->user();

		// Redirect home if user does not have the minimum level
		if (( ! $this->user AND $this->minimum_level > 0) OR $this->user['level'] < $this->minimum_level)
		{
			Request::instance()->redirect(BASEURL);
		}

		// Add the template to the template variable
		$this->template = $this->template ? View::factory($this->template) : $this->template;
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		// Render the template if it is set and has content
		if ($this->template AND $this->template->data())
		{
			Response::instance()
				->body($this->template->render())
				->render();
		}
	}

} // End Controller
