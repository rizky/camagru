<?php

class Home extends Controller
{
	protected $user;
	protected $view;

	public function __construct()
	{
		$this->user = $this->model('User');
		$this->view = $this->view('Home');
	}

	public function index($name = '')
	{
		$this->user->name = $name;
		$this->view->params = ['user' => $this->user];
		$this->view->render();
	}
}