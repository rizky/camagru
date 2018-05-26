<?php

class Comments extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function delete()
	{
		if (!isset($_POST['comment']))
			http_response_code(400);
		$comment = Comment::get(array('id' => $_POST['comment']));
		if ($comment == NULL)
			http_response_code(400);
		if (!$this->authenticate($comment->user))
			http_response_code(401);
		$comment->delete();
	}

	public function insert()
	{

		if (!isset($_POST['comment']) || !isset($_POST['user']) || !isset($_POST['photo']))
			http_response_code(400);
		if (!$this->authenticate($_POST['user']))
			http_response_code(401);
		$sender = User::get(array('username' => $_POST['user']));
		$photo = Photo::get(array ('id' => $_POST['photo']));
		$recipient = User::get(array('username' => $photo['user']));
		$comment = $sender->insert_comment($photo, $_POST['comment']);
		$comment->populate();
		if ($recipient->subscribed)
			$this->sendNotification($recipient, $comment);
	}

	public function sendNotification(User $user, Comment $comment)
	{
		$to = $user->email;
		$subject = 'Camagru Notification';
		$headers = array(
			'From' => 'Admin Camagru <camagru.rizky@gmail.com>',
			'Reply-To' => 'Admin Camagru <camagru.rizky@gmail.com>',
			'MIME-Version' => '1.0',
			'Content-Type' => 'text/html; charset=UTF-8',
		);
		$message = $this->view('email/notification', array('user' => $user, 'comment' => $comment))->dump();
		mail($to, $subject, $message, $headers);
	}
}
