<?php

class Account extends Controller
{
	protected $user;
	protected $view;

	public function __construct()
	{
	}

	public function index($user = '')
	{
		if ($user != '')
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
}
