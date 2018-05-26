<?php

class Camera extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if ($this->user == NULL)
			$this->redirect('/account/login');
		$stickers = scandir("img/stickers/");
		unset($stickers[0], $stickers[1]);
		$this->view = $this->view('camera/index', array('stickers' => $stickers))->render();
	}
}
