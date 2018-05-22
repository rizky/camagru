<?php

class Camera extends Controller
{
	protected $user;
	protected $view;

	public function __construct()
	{
	}

	public function index()
	{
		$this->view = $this->view('camera/index');
		$this->view->render();
	}
}
