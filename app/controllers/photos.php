<?php

class Photos extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index($id = '')
	{
		if ($id == '')
		{
			$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
			$photos = Photo::find([], $offset);
			$more_v = count($photos) < 5 ? 'hidden' : 'show';
			$this->view = $this->view('photos/index', array('photos' => $photos, 'offset' => count($photos) + $offset, 'more_v' => $more_v));
		}
		else
		{
			$photo = Photo::get(array ('id' => $id));
			if ($photo == NULL)
				$this->redirect('/');
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
		$this->user->like($photo);
		$this->redirect('/photos/' . $photo['id']);
	}

	public function delete()
	{
		if (!isset($_POST['photo']))
			$this->redirect('/');
		$photo = Photo::get(array('id' => $_POST['photo']));
		if ($photo == NULL)
			$this->redirect('/');
		if (!$this->authenticate($photo['user']))
			return (false);
		$photo['object']->delete();
	}
}
