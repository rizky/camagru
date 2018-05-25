<?php

class Photos extends Controller
{
	protected $view;

	public function __construct()
	{
		parent::__construct();
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
			$photo = Photo::get(array ('id' => $id));
			$comments = Comment::find(array ('photo' => $photo['id']));
			$this->view = $this->view('photos/show', array('photo' => $photo, 'comments' => $comments));
		}
		$this->view->render();
	}

	public function like($id = '')
	{
		if ($id == '' || $this->user == NULL)
			$this->redirect('/account/login');
		$photo = Photo::get(array ('id' => $id));
		$comments = Comment::find(array ('photo' => $photo['id']));
		$this->user->like($photo);
		$this->redirect('/photos/' . $photo['id']);
	}
}
