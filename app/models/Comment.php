<?php

class Comment extends Model
{
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

	static public function get(array $params=[])
	{
		$comment = Comment::findOne($params);
		$comment->populate();
		if ($comment instanceof Comment)
			return ($comment);
		else
			return (NULL);
		return (NULL);
	}

	static public function checkMaxWord($message)
	{
		$words = explode(' ', $message);
		$maxLength = 0;
		foreach ($words as $word) {
			if ($maxLength < strlen($word))
				$maxLength = strlen($word);
		}
		return ($maxLength);
	}

	static public function find(array $params = [])
	{
		$comments = Comment::findAll($params, array('createdAt', 'ASC'), []);
		
		foreach ($comments as &$c)
		{
			$c['user'] = User::get(array('id' => $c['user']))->username;
			$c['message'] =  htmlspecialchars($c['message']);
			$c['delete_v'] = Comment::ownedBy($c['user'] );
			$c['break'] = Comment::checkMaxWord($c['message']) > 40 ? 'block' : 'inline';
		}
		return $comments;
	}

	public function insert(User $user, $photo)
	{
		$this->user = $user->id;
		if ($photo instanceof Photo)
			$this->photo = $photo->id;
		else
			$this->photo = $photo['id'];
		$this->id = Comment::store(get_object_vars($this));
	}

	public function populate()
	{
		$this->user = User::get(array('id' => $this->user))->username;
	}

	static public function ownedBy($user)
	{
		if (isset($_SESSION['user']))
			$current_user = unserialize($_SESSION['user']);
		else
			return 'hidden';
		return 	($user != $current_user->username) ? 'hidden' : 'show';
	}
}