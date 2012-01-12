<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Execute a benchmark and show the results to the user.
 *
 * @package   PHPMVC
 * @category  Controller
 * @author    T. Zengerink
 */
class Controller_Benchmark extends Controller {

	/**
	 * @var  string  template name
	 */
	public $template = 'template';

	/**
	 * Constructor
	 *
	 * @uses  Config
	 * @uses  Request
	 */
	public function __construct()
	{
		// Execute parent's constructor
		parent::__construct();

		// Redirect if in production, no benchmark name given or when benchmark does not exist
		$production  = Config::load('core')->get('is_production'); 
		$name        = Request::instance()->param('name');
		$file_exists = file_exists(APPROOT.'benchmark/'.$name.'.php');

		if ($production OR ! $name OR ! $file_exists)
		{
			Request::instance()->redirect(BASEURL);
		}
	}

	/**
	 * Action Index
	 *
	 * @uses  Benchmark
	 * @uses  Request
	 */
	public function action_index()
	{
		// Execute benchmark
		$benchmark = Benchmark::factory(Request::instance()->param('name'))
			->run()
			->render();

		// Render template with results as content
		$this->template->set('content', $benchmark);
	}

} // End Controller Benchmark
