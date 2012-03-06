<?php defined('DOCROOT') or die('No direct access allowed.');

class Benchmark_Uristring {

	public $webroot = 'some/web/root/';

	public $uri = 'some/web/root/example/action/id.extension';

	public function str_replace()
	{
		return str_replace($this->webroot, '', $this->uri);
	}

	public function substr()
	{
		return substr($this->uri, strlen($this->webroot));
	}

	public function preg_replace()
	{
		return preg_replace('#'.$this->webroot.'#', '', $this->uri);
	}

	public function explode()
	{
		$explode = explode($this->webroot, $this->uri);
		return $explode[1];
	}

}
