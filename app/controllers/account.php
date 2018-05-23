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

	public function index($id = '')
	{
		if ($this->user !== NULL && $id == '')
			$id = $this->user->id;
		if ($id == '')
			$this->redirect('/account/login');
		$photos = Photo::find(array('user' => $id));
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
				$_SESSION['user'] = (array)($user);
				$this->redirect('/photo/show');
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
		$this->view = $this->view('photos/index');
		$this->view->render();
	}
}
