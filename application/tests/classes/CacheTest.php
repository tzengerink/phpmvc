<?php
if ( ! defined('DOCROOT')) define('DOCROOT', dirname(__FILE__).'/../../../');
if ( ! defined('APPROOT')) define('APPROOT', dirname(__FILE__).'/../../../application/');

require_once(APPROOT.'classes/file.php');
require_once(APPROOT.'classes/cache.php');

class CacheTest extends PHPUnit_Framework_TestCase {

	public function testSet()
	{
		$array = Cache::set('array', array(
			'hello' => 'world!',
			'and'   => 'another',
			'some'  => 'more',
			'key'   => 'value',
		));	

		$this->assertTrue($array);
	}

	/**
	 * @depends  testSet
	 */
	public function testGet()
	{
		$array = Cache::get('array'); 
		$false = Cache::get('is_empty');

		$this->assertEquals(count($array), 4);
		$this->assertArrayHasKey('hello', $array);
		$this->assertFalse($false);
	}

	/**
	 * @depends testGet
	 */
	public function testLifetime()
	{
		Cache::$lifetime = -3600;

		$array = Cache::get('array');
		
		$this->assertFalse($array);
		
		Cache::$lifetime = 3600;
	}

	/**
	 * @depends  testGet
	 */
	public function testClear()
	{
		$array = Cache::clear('array');

		$this->assertTrue($array);
	}

} // End Cache Test
