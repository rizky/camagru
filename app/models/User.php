<?php

class User
{
	public $id;
	public $username;
	public $password;
	public $name;
	public $email;
	public $tokenValidated;
	public $tokenLost;

	public function __construct(array $params = [])
	{
		array_key_exists('id', $params) ? $this->id = $params['id'] : 0;
		array_key_exists('username', $params) ? $this->username = $params['username'] : 0;
		array_key_exists('password', $params) ? $this->password = $params['password'] : 0;
		array_key_exists('name', $params) ? $this->name = $params['name'] : 0;
		array_key_exists('email', $params) ? $this->email = $params['email'] : 0;
	}

	static public function encrypt_password($username, $password)
	{
		return sha1("c4m4gru" . $username . $password);
	}

	static public function login($username, $password)
	{
		$user = ORM::getInstance()->findOne('user', array('username' => $username, 'password' => User::encrypt_password($username, $password)));
		if ($user instanceof User) {
			if (empty($user->tokenValidated))
				return ($user);
			else
				return (-1);
		}
		return (NULL);
	}

	static public function get(array $params=[])
	{
		$user = ORM::getInstance()->findOne('user', $params);
		if ($user instanceof User) {
			if (empty($user->tokenValidated))
				return ($user);
			else
				return (-1);
		}
		return (NULL);
	}

	public function delete()
	{
		$user = ORM::getInstance()->findOne('user', array('username' => $this->username));
		if ($user instanceof User)
			return ORM::getInstance()->delete_s('user', $user->id);
		return (false);
	}

	public function insert()
	{
		$this->id = ORM::getInstance()->store('user', get_object_vars($this));
	}

	public function insert_photo(Photo $photo)
	{
		$photo->insert($this);
	}

	public function insert_comment(Photo $photo, Comment $comment)
	{
		$comment->insert($this, $photo);
	}
}
