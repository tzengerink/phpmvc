<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Default controller to execute.
 *
 * @package   PHPMVC
 * @category  Controller
 * @author    T. Zengerink
 */
class Controller_Index extends Controller {

	/**
	 * @var  string  template
	 */
	public $template = 'template';

	/**
	 * Default action to execute.
	 */
	public function action_index()
	{
		$this->template->set('content', '<br /><center>Hello, World!</center>');
	}

} // End Controller Index
