<?php

class Controller
{
	protected $method = 'GET';
	public function __construct()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
			$this->method = 'POST';
	}

	protected function view($view, $params = [])
	{
		return new View($view, $params);
	}
}