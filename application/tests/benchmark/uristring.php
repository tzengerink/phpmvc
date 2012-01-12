<?php
if ( ! defined('DOCROOT')) define('DOCROOT', dirname(__FILE__).'/../../../');
if ( ! defined('APPROOT')) define('APPROOT', dirname(__FILE__).'/../../../application/');

require_once(APPROOT.'benchmark/uristring.php');

class BenchmarkUristringTest extends PHPUnit_Framework_TestCase {

	public $benchmark;

	public $result;

	public function setUp()
	{
		$this->benchmark = new Benchmark_Uristring;
		$this->result    = 'example/action/id.extension';
	}

	public function testStr_replace()
	{
		$this->assertEquals($this->result, $this->benchmark->str_replace());
	}
	
	public function testSubstr()
	{
		$this->assertEquals($this->result, $this->benchmark->substr());
	}

	public function testPreg_replace()
	{
		$this->assertEquals($this->result, $this->benchmark->preg_replace());
	}
	
	public function testExplode()
	{
		$this->assertEquals($this->result, $this->benchmark->explode());
	}

} // End Benchmark Uristring Test
