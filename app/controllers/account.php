<?php

class Account extends Controller
{
	protected $user;
	protected $view;

	public function __construct()
	{
		parent::__construct();
		if (isset($_SESSION['user']))
			$this->user = unserialize($_SESSION['user']);
	}

	public function index($username = '')
	{
		if ($this->user !== NULL && $username == '')
			$username = $this->user->username;
		if ($username == '')
			$this->redirect('/account/login');
		$photos = Photo::find(array('user' => $username));
		$this->view = $this->view('photos/index', array('photos' => $photos));
		$this->view->render();
	}

	public function login()
	{
		if ($this->method === 'POST')
		{
			$user = User::Login($_POST['username'], $_POST['password']);
			if ($user instanceof User)
			{
				$_SESSION['user'] = serialize($user);
				$this->redirect('/');
			}
		}
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
		$this->redirect('/');
	}
}
