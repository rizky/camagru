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
		$this->view = $this->view('photos/showAll');
		$this->user->name = $name;
		$this->view->params = ['user' => (array)$this->user];
		$this->view->render();
	}
}