<?php

class Camera extends Controller
{
	protected $view;

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if ($this->user == NULL)
			$this->redirect('/account/login');
		$this->view = $this->view('camera/index')->render();
	}
}
