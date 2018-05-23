<?php

class Account extends Controller
{
	protected $user;
	protected $view;

	public function __construct()
	{
		parent::__construct();
		if (isset($_SESSION['user']))
			$this->user = (object)$_SESSION['user'];
	}

	public function index()
	{
		if ($this->user == NULL)
			$this->redirect('/account/login');
		$this->view = $this->view('account/index');
		$this->view->render();
	}

	public function login()
	{
		if ($this->method === 'POST')
		{
			$user = User::Login($_POST['username'], $_POST['password']);
			if ($user instanceof User)
			{
				$_SESSION['user'] = (array)($user);
				$this->redirect('/photo/show');
			}
		}
		$this->view = $this->view('account/login');
		$this->view->render();
	}

	public function profile()
	{
		if ($this->user == NULL)
			$this->redirect('/account/login');
		$this->view = $this->view('photos/show');
		$this->view->params = ['user' => (array)$this->user];
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
