<?php

class Account extends Controller
{
	protected $view;

	public function __construct()
	{
		parent::__construct();
	}

	public function index($username = '')
	{
		if ($this->user !== NULL && $username == '')
			$username = $this->user->username;
		if ($username == '')
			$this->redirect('/account/login');
		if (User::get(array('username' => $username)) == -1)
			$this->redirect('/');
		$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
		$photos = Photo::find(array('user' => $username), $offset);
		$more_v = count($photos) == 0 ? 'hidden' : 'show';
		$this->view = $this->view('photos/index', array('photos' => $photos, 'offset' => count($photos) + $offset, 'more_v' => $more_v));
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
		$this->view('account/login')->render();
	}

	public function register()
	{
		$this->view('account/register')->render();
	}

	public function settings()
	{
		if ($this->user == NULL)
			$this->redirect('/account/login');
		$this->view('/account/settings')->render();
	}

	public function logout()
	{
		unset($_SESSION['user']);
		$this->redirect('/');
	}
}
