<?php

class Controller
{
	protected $method = 'GET';
	protected $user;
	protected $view;

	public function __construct()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
			$this->method = 'POST';
		if (isset($_SESSION['user']))
			$this->user = unserialize($_SESSION['user']);
		if (ORM::testConnection() == false)
			$this->redirect('/setup');
	}

	public function view($view, $params = [])
	{
		return new View($view, $params);
	}

	protected function redirect($url)
	{
		header('Location: ' . $url);
		exit;
	}

	protected function authenticate($user)
	{
		if ($this->user == NULL)
			return false;
		if ($user != $this->user.username)
			return false;
		else
			return true;
	}
}