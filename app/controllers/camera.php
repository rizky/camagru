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

		if ($this->user !== NULL && $username == '')
		$username = $this->user->username;
		if ($username == '')
			$this->redirect('/account/login');
		$user = User::get(array('username' => $username));
		if ($user == NULL)
			$this->redirect('/');
		$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
		$photos = Photo::find(array('user' => $user->id), $offset);
		$more_v = count($photos) < 5 ? 'hidden' : 'show';
		$this->view = $this->view('camera/index', array('stickers' => $stickers, 'photos' => $photos, 'username' => $username, 'offset' => count($photos) + $offset, 'more_v' => $more_v))->render();
	}
}
