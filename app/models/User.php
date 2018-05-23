<?php

class User
{
	public $id;
	public $username;
	public $password;
	public $name;
	public $tokenValidated;
	public $tokenLost;


	static public function encrypt_password($username, $password)
	{
		return sha1("c4m4gru" . $username . $password);
	}

	static public function login($username, $password)
	{
		$user = ORM::getInstance()->findOne('user', array('username' => $username, 'password' => User::encrypt_password($username, $password)));
		if ($user instanceof User) {
			if (empty($user->tokenValidated))
				return $user;
			else
				return 1;
		}
		return (NULL);
	}
}
