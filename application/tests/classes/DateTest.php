<?php
if ( ! defined('DOCROOT')) define('DOCROOT', dirname(__FILE__).'/../../../');
if ( ! defined('APPROOT')) define('APPROOT', dirname(__FILE__).'/../../../application/');

require_once(APPROOT.'classes/date.php');

class DateTest extends PHPUnit_Framework_TestCase {

	public $date;

	public $time;

	public function setUp()
	{
		date_default_timezone_set('Europe/Amsterdam');

		$this->time = time();
		$this->date = Date::factory($this->time);
	}

	public function testFactory()
	{
		$this->assertTrue($this->date instanceof Date);
		$this->assertEquals($this->date->timestamp, $this->time);
	}

	public function testFormat()
	{
		$format = 'd-m-Y H:i:s';

		$this->assertEquals($this->date->format(), date($this->date->format, $this->time));
		$this->assertEquals($this->date->format($format), date($format, $this->time));
	}

} // End Date Test
