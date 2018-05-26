<?php

class User extends Model
{
	public $username;
	public $password;
	public $password2;
	public $name;
	public $email;
	public $tokenValidated;
	public $tokenLost;
	public $subscribed = 1;

	public function __construct(array $params = [])
	{
		array_key_exists('id', $params) ? $this->id = $params['id'] : 0;
		array_key_exists('username', $params) ? $this->username = $params['username'] : 0;
		array_key_exists('password', $params) ? $this->password = $params['password'] : 0;
		array_key_exists('name', $params) ? $this->name = $params['name'] : 0;
		array_key_exists('email', $params) ? $this->email = $params['email'] : 0;
	}

	static public function encrypt_password($password)
	{
		return sha1("c4m4gru" . $password);
	}

	static public function login($username, $password)
	{
		$user = User::findOne(array('username' => $username, 'password' => User::encrypt_password($password)));
		if ($user instanceof User) {
			if (empty($user->tokenValidated))
				return ($user);
			else
				return (NULL);
		}
		return (NULL);
	}

	public function register()
	{
		$errors[] = $this->checkUsername();
		$errors[] = $this->checkPassword();
		$errors[] = $this->checkEmail();
		foreach ($errors as $e) {
			if (!empty($e))
				return ($errors);
		}
		$this->password = User::encrypt_password($this->password);
		$this->tokenValidated = $this->generateKey();
		$this->insert();
	}

	static public function get(array $params=[])
	{
		$user = User::findOne($params);
		if ($user instanceof User) {
			if (empty($user->tokenValidated))
				return ($user);
			else
				return (NULL);
		}
		return (NULL);
	}

	public function insert()
	{
		$this->id = User::store(get_object_vars($this));
	}

	public function insert_photo(Photo $photo)
	{
		$photo->insert($this);
	}

	public function insert_comment($photo, $comment)
	{
		$comment = new Comment(array(
			'message' => $comment)
		);
		$comment->insert($this, $photo);
		return $comment;
	}

	public function like($photo)
	{
		$like = new Like;
		$like->insert($this, $photo);
	}

	public function update(User $user)
	{
		$errors = [];
		if ($user->username != $this->username)
			$errors[] = $user->checkUsername();
		if ($user->email != $this->email)
			$errors[] = $user->checkEmail();
		foreach ($errors as $e) {
			if (!empty($e))
				return ($errors);
		}
		$user->insert();
	}

	public function change_password(User $user, $password_old)
	{
		$errors = [];
		$u = User::Login($user->username, $password_old);
		if ($u == NULL)
			$errors[] = "Old Password is incorrect";
		$errors[] = $user->checkPassword();
		foreach ($errors as $e) {
			if (!empty($e))
				return ($errors);
		}
		$user->password = User::encrypt_password($user->password);
		$user->insert();
	}

	static public function validateEmail($key)
	{
		$user = User::findOne(array('tokenValidated' => $key));
		if ($user instanceof User)
		{
			$user->tokenValidated = NULL;
			User::store(get_object_vars($user));
			return (true);
		}
		return (false);
	}

	private function generateKey()
	{
		$key = "";
		$alpha = "abcdefghijklmnpqrstuvwxyABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		srand((double)microtime() * 1000000);
		for ($i = 0; $i < 50; $i++) {
			$key .= $alpha[rand() % strlen($alpha)];
		}
		return $key . md5($this->email);
	}
	private function checkUsername()
	{
		if (User::findOne(array('username' => $this->username)) instanceof User)
			return 'Username is taken';
		if (!preg_match('/^([a-zA-Z0-9-_.]){3,20}$/', $this->username))
			return 'Username should consist of 3 to 20 character of alpha numeric';
		return;
	}
	private function checkPassword()
	{
		if ($this->password !== $this->password2)
			return 'Password is not match';
		if (strlen($this->password) < 6 || strlen($this->password) > 40)
			return 'Password should consist of 6 to 40 character';
		return;
	}
	private function checkEmail()
	{
		if (User::findOne(array('email' => $this->email)) instanceof User)
			return 'Email address has been used';
		if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
			return 'Email address is not valid';
		return;
	}
}
