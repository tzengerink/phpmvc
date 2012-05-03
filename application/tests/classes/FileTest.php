<?php
if ( ! defined('DOCROOT')) define('DOCROOT', dirname(__FILE__).'/../../../');
if ( ! defined('APPROOT')) define('APPROOT', dirname(__FILE__).'/../../../application/');

require_once(APPROOT.'classes/file'.EXT);

class FileTest extends PHPUnit_Framework_TestCase {

	public $file;

	public function setUp()
	{
		$this->file = File::factory(APPROOT.'cache/testfile.cache', 0777);
	}

	public function testFactory()
	{
		$this->assertTrue($this->file instanceof File);
	}

	public function testAppendAndRead()
	{
		$this->file->append('teststring');
		
		$this->assertEquals($this->file->read(), 'teststring');
	}
	
	/**
	 * @depends  testAppendAndRead
	 */
	public function testTail()
	{
		$this->assertEquals($this->file->tail(), 'teststring');
	}

	/**
	 * @depends  testAppendAndRead
	 */
	public function testWriteAndRead()
	{
		$this->file->write('Put a new line in there');

		$this->assertEquals($this->file->read(), 'Put a new line in there');
	}

	/**
	 * @depends  testWriteAndRead
	 */
	public function testDelete()
	{
		$this->assertTrue($this->file->delete());
		$this->assertFalse(file_exists($this->file->path));
	}

} // End File Test
