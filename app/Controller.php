<?php

class Controller
{
	protected $method = 'GET';
	public function __construct()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
			$this->method = 'POST';
	}

	protected function model($model)
	{
		require_once 'app/models/' . $model . '.php';
		return new $model();
	}

	protected function view($view, $params = [])
	{
		return new View($view, $params);
	}
}