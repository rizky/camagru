<?php

class Api extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function photos($id = '')
	{
		if ($id == '')
		{
			$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
			$username = isset($_GET['username']) ? $_GET['username'] : NULL;
			if ($username != Null)
			{
				$user = User::get(['username' => $username]);
				$photos = Photo::find(['user' => $user->id], $offset);
			}
			else
				$photos = Photo::find([], $offset);
			$more_v = count($photos) == 0 ? 'hidden' : 'show';
			$this->view = $this->view('api/photos', array('photos' => $photos, 'offset' => count($photos) + $offset, 'more_v' => $more_v));
			$this->view->render();
		}
		else
			http_response_code(400);
	}
}
