<?php

class Photos extends Controller
{
	protected $user;
	protected $view;

	public function __construct()
	{
		$this->user = $this->model('User');
	}

	public function showAll($name = '')
	{
		$this->view = $this->view('Photos/showAll');
		$this->user->name = $name;
		$this->view->params = ['user' => $this->user];
		$this->view->render();
	}
}