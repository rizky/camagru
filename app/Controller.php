<?php

class Controller
{
	protected $method = 'GET';
	protected $user;

	public function __construct()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
			$this->method = 'POST';
		if (isset($_SESSION['user']))
			$this->user = unserialize($_SESSION['user']);
	}

	protected function view($view, $params = [])
	{
		return new View($view, $params);
	}

	protected function redirect($url)
	{
		header('Location: ' . $url);
		exit;
	}
}