<?php

class Comments extends Controller
{
	protected $user;
	protected $view;

	public function __construct()
	{
		if (isset($_SESSION['user']))
			$this->user = unserialize($_SESSION['user']);
	}

	public function delete()
	{
		if (!isset($_POST['comment']))
			$this->redirect('/');
		$comment = new Comment(array('id' => $_POST['comment']));
		$comment->delete();
		echo 'OK';
	}
}
