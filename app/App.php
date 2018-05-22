<?php
require_once 'Controller.php';
require_once 'View.php';

class App
{
	protected $controller = 'Photos';
	protected $method = 'showAll';
	protected $params = [];

	public function __construct()
	{
		$url = $this->parse_url();
		
		if (file_exists('app/controllers/' . $url[0] . '.php'))
		{
			$controller = $url[0];
			unset($url[0]);
		}
		require_once 'app/controllers/' . $this->controller . '.php';
		$this->controller = new $this->controller;
		if (isset($url[1]) && method_exists($this->controller, $url[1]))
		{
			$this->method = $url[1];
			$this->controller = $controller;
			unset($url[1]);
		}
		$this->params = $url ? array_values($url) : [];
		call_user_func_array([$this->controller, $this->method], $this->params);
	}

	public function parse_url()
	{
		if (isset($_GET['url']))
		{
			return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
		}
	}
}
