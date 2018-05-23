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
		if ($id == '')
		{
			$photos = Photo::find();
			$this->view = $this->view('photos/index', array('photos' => $photos));
		}
		else
		{
			$photos = Photo::find(array ('id' => $id));
			$this->view = $this->view('photos/index', array('photos' => $photos));
		}
		$this->view->render();
	}

	public function show($id = '')
	{
		$this->view = $this->view('photos/show');
		$this->view->render();
	}
}
