<?php

class Photos extends Controller
{
	protected $user;
	protected $view;

	public function __construct()
	{
		if (isset($_SESSION['user']))
			$this->user = unserialize($_SESSION['user']);
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
		if ($id == '')
			$this->redirect('/');
		$photo = Photo::get(array ('id' => $id));
		$comments = Comment::find(array ('photo' => $photo['id']));
		$this->user->like($photo);
		$this->redirect('/photos/' . $photo['id']);
	}
}
