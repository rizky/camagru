<?php

class Like extends Model
{
	public $user;
	public $photo;

	public function __construct(array $params = [])
	{
		array_key_exists('user', $params) ? $this->user = $params['user'] : 0;
		array_key_exists('photo', $params) ? $this->photo = $params['photo'] : 0;
	}

	static public function get(array $params=[])
	{
		$like = Like::findOne($params);
		if ($like instanceof Like)
			return ($like);
		else
			return (NULL);
		return (NULL);
	}

	public function insert(User $user, $photo)
	{
		$this->user = $user->id;
		if ($photo instanceof Photo)
			$this->photo = $photo->id;
		else
			$this->photo = $photo['id'];
		$like = Like::get(array('photo' => $this->photo, 'user' => $this->user));
		if ($like == NULL)
			$this->id = Like::store(get_object_vars($this));
		else
		{
			$this->id = $like->id;
			if ($like->user != $user->id)
				$this->id = Like::store(get_object_vars($this));
			else
				$this->delete();
		}
	}

	static public function find(array $params = [])
	{
		$likes = Like::findAll($params, array('createdAt', 'ASC'), []);
		foreach ($likes as &$l)
			$l['user'] = USER::get(array('id' => $l['user']))->username;
		return $likes;
	}

	public function populate()
	{
		$this->user = USER::get(array('id' => $this->user))->username;
	}

	static public function is_user_like($likes)
	{
		$result = 0;
		foreach ($likes as $like)
		{
			if (Like::ownedBy($like['user']) == 'show')
			{
				$like['user'] = 'you';
				return (1);
			}
		}
		return (0);
	}

	static public function ownedBy($user)
	{
		if (isset($_SESSION['user']))
			$current_user = unserialize($_SESSION['user']);
		return 	($user != $current_user->username) ? 'hidden' : 'show';
	}
}