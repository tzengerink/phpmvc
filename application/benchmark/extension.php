<?php defined('DOCROOT') or die('No direct access allowed.');

class Benchmark_Extension {

	public $filename = '/Some/File/Path/to/test/the/extension/finder.jpg';

	public function explode()
	{
		$parts = explode('.', $this->filename);
		return $parts[count($parts) - 1];
	}

	public function strrchr()
	{
		return substr(strrchr($this->filename, '.'), 1);
	}

	public function strrchr2()
	{
		return strrchr($this->filename, '.');
	}

	public function strrpos()
	{
		return substr($this->filename, strrpos($this->filename, '.') + 1);
	}

	public function regex()
	{
		return preg_replace('/^.*\.([^.]+)$/D', '$1', $this->filename);
	}
	        
	public function regex2()
	{
		return preg_replace('/\.([^.]+)$/', '$1', $this->filename);
	}
			          
	public function pathinfo()
	{
		return pathinfo($this->filename, PATHINFO_EXTENSION);
	}

}
