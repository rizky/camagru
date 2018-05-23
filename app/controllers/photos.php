<?php

class Photos extends Controller
{
	protected $user;
	protected $view;

	public function __construct()
	{
		if (isset($_SESSION['user']))
			$this->user = (object)$_SESSION['user'];
	}

	public function index($id = '')
	{
		$this->view = $this->view('photos/index');
		$this->view->render();
	}

	public function show($id = '')
	{
		$this->view = $this->view('photos/show');
		$this->view->render();
	}

	public function user($id = '')
	{
		if ($this->user == NULL)
			$this->view = $this->view('account/login');
		else
		{
			$this->view = $this->view('photos/show');
			$this->user->id = $id;
			$this->view->params = ['user' => (array)$this->user];
		}
		$this->view->render();
	}
}
