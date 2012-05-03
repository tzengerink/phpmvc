<?php
if ( ! defined('DOCROOT')) define('DOCROOT', dirname(__FILE__).'/../../../');
if ( ! defined('APPROOT')) define('APPROOT', dirname(__FILE__).'/../../../application/');

require_once(APPROOT.'classes/router'.EXT);

class RouterTest extends PHPUnit_Framework_TestCase {

	public $router_1;

	public $router_2;

	public function setUp()
	{
		$this->router_1 = Router::factory();
		$this->router_2 = Router::factory('image/get/123.png');
	}

	public function testFactory()
	{
		$this->assertTrue($this->router_1 instanceof Router);
		$this->assertTrue($this->router_2 instanceof Router);
		$this->assertEquals($this->router_1->uri, '');
		$this->assertEquals($this->router_2->uri, 'image/get/123.png');
	}

	public function testGet()
	{
		$this->assertEquals($this->router_1->get('controller'), 'index');
		$this->assertEquals($this->router_1->get('action'), 'index');
		$this->assertEquals($this->router_2->get('controller'), 'image');
		$this->assertEquals($this->router_2->get('action'), 'get');
		$this->assertEquals($this->router_2->get('id'), '123');
		$this->assertEquals($this->router_2->get('extension'), '.png');
	}

	public function testRoute()
	{
		$this->router_1->route();
		$this->router_2->route();

		$this->assertTrue($this->router_1->route !== NULL);
		$this->assertTrue($this->router_2->route !== NULL);
	}

} // End Router Test
