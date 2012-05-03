<?php
if ( ! defined('DOCROOT')) define('DOCROOT', dirname(__FILE__).'/../../../');
if ( ! defined('APPROOT')) define('APPROOT', dirname(__FILE__).'/../../../application/');

require_once(APPROOT.'classes/config'.EXT);
require_once(APPROOT.'classes/router'.EXT);
require_once(APPROOT.'classes/request'.EXT);

class RequestTest extends PHPUnit_Framework_TestCase {

	public $request;

	public function setUp()
	{
		$this->request = Request::instance();
	}

	public function testInstance()
	{
		$this->assertTrue($this->request instanceof Request);	
	}

	public function testParam()
	{
		$this->assertEquals($this->request->param('controller'), 'index');
		$this->assertEquals($this->request->param('action'), 'index');
	}

} // End Request Test
