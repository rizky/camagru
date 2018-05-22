<?php

class User
{
	public $id;
	public $username;
	public $password;
	public $name;
	public $tokenValidated;
	public $tokenLost;


	static private function encrypt_password($username, $password)
	{
		return sha1("c4m4gru" . $username . $password);
	}
}
