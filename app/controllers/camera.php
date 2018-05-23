<?php

class Camera extends Controller
{
	protected $user;
	protected $view;

	public function __construct()
	{
		if (isset($_SESSION['user']))
			$this->user = (object)$_SESSION['user'];
	}

	public function index()
	{
		$this->view = $this->view('camera/index');
		$this->view->render();
	}
}
