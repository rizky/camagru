<?php

class Comment
{
	public $id;
	public $user;
	public $photo;
	public $message;

	public function __construct(array $params = [])
	{
		array_key_exists('id', $params) ? $this->id = $params['id'] : 0;
		array_key_exists('user', $params) ? $this->user = $params['user'] : 0;
		array_key_exists('photo', $params) ? $this->photo = $params['photo'] : 0;
		array_key_exists('message', $params) ? $this->message = $params['message'] : 0;
	}

	public function delete()
	{
		$comment = ORM::getInstance()->findOne('comment', array('id' => $this->id));
		if (!$this->authenticate($comment->user))
			return (false);
		if ($comment instanceof Comment)
			return ORM::getInstance()->delete_s('comment', $comment->id);
		return false;
	}

	public function insert(User $user, $photo)
	{
		$this->user = $user->username;
		if ($photo instanceof Photo)
			$this->photo = $photo->id;
		else
			$this->photo = $photo['id'];
		$this->id = ORM::getInstance()->store('comment', get_object_vars($this));
	}

	static public function ownedBy($user)
	{
		if (isset($_SESSION['user']))
			$current_user = unserialize($_SESSION['user']);
		else
			return 'hidden';
		return 	($user != $current_user->username) ? 'hidden' : 'show';
	}

	static public function find(array $params = [])
	{
		$comments = ORM::getInstance()->findAll('comment', $params, array('createdAt', 'ASC'), []);
		
		foreach ($comments as &$c)
			$c['delete_v'] = Comment::ownedBy($c['user'] );
		return $comments;
	}
}