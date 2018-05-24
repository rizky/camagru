<?php

class Comment
{
	public $id;
	public $user;
	public $photo;
	public $message;

	public function __construct(array $params = [])
	{
		array_key_exists('user', $params) ? $this->user = $params['user'] : 0;
		array_key_exists('photo', $params) ? $this->photo = $params['photo'] : 0;
		array_key_exists('message', $params) ? $this->message = $params['message'] : 0;
	}

	public function delete()
	{
		$comment = ORM::getInstance()->findOne('comment', array('id' => $this->id));
		if ($comment instanceof Comment)
			return ORM::getInstance()->delete_s('comment', $comment->id);
		return false;
	}

	public function insert()
	{
		$this->id = ORM::getInstance()->store('comment', get_object_vars($this));
	}

	static public function find(array $params = [])
	{
		$comments = ORM::getInstance()->findAll('comment', $params, array('createdAt', 'DESC'), []);
		return $comments;
	}
}