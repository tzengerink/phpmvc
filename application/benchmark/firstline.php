<?php defined('DOCROOT') or die('No direct access allowed.');

class Benchmark_FirstLine {

	public $string = "Hello World!\nThis is just\nsome test string";

	public function substr()
	{
		return substr($this->string, 0, strpos($this->string, "\n"));
	}

	public function substr2()
	{
		return substr($this->string, 0, strpos($this->string, chr(10)));
	}

	public function strtok()
	{
		return strtok($this->string, "\n");
	}

	public function explode()
	{
		list($line_1, $remaining) = explode("\n", $this->string, 2);
		return $line_1;
	}

	public function str_replace()
	{
		return str_replace(strstr($this->string, "\n"), "", $this->string);
	}

}
