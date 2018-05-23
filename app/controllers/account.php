<?php

class Account extends Controller
{
	protected $user;
	protected $view;

	public function __construct()
	{
		if (isset($_SESSION['user']))
			$this->user = (object)$_SESSION['user'];
	}

	public function index($user = '')
	{
		if ($this->user)
		{
			$this->view = $this->view('account/index');
			$this->view->render();
		}
		else
			$this->login();
	}

	public function login()
	{
		$this->view = $this->view('account/login');
		$this->view->render();
	}

	public function register()
	{
		$this->view = $this->view('account/register');
		$this->view->render();
	}

	public function logout()
	{
		unset($_SESSION['user']);
		$this->view = $this->view('photos/index');
		$this->view->render();
	}
}
