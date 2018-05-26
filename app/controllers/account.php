<?php

class Account extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index($username = '')
	{
		if ($this->user !== NULL && $username == '')
			$username = $this->user->username;
		if ($username == '')
			$this->redirect('/account/login');
		$user = User::get(array('username' => $username));
		if ($user == NULL)
			$this->redirect('/');
		$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
		$photos = Photo::find(array('user' => $user->id), $offset);
		$more_v = count($photos) == 0 ? 'hidden' : 'show';
		$this->view = $this->view('photos/index', array('photos' => $photos, 'offset' => count($photos) + $offset, 'more_v' => $more_v));
		$this->view->render();
	}

	public function login()
	{
		$errors = [];
		if ($this->method === 'POST' && isset($_POST['username']) && isset($_POST['password']))
		{
			$user = User::Login($_POST['username'], $_POST['password']);
			if ($user)
			{
				if (empty($user->tokenValidated))
				{
					$_SESSION['user'] = serialize($user);
					$this->redirect('/');
				}
				else
					$errors[] = 'Your account has not been validated. Check your email!';
			}
			else
				$errors[] = 'Sorry, your password was incorrect';
		}
		$this->view('account/login', array('errors' => $errors))->render();
	}

	public function conformation()
	{
		if (isset($_GET['key']) && User::validateEmail($_GET['key']))
			$message = 'Your account has been validated';
        else
			$message = 'Invalid key';
		$this->view('account/confirmation', array('message' => $message))->render();
	}

	public function register()
	{
		if ($this->method === 'POST')
		{
			$user = new User;
			$user->email = isset($_POST['email']) ? $_POST['email'] : NULL;
			$user->username = isset($_POST['username']) ? $_POST['username'] : NULL;
			$user->password = isset($_POST['password']) ? $_POST['password'] : NULL;
			$user->password2 = isset($_POST['password2']) ? $_POST['password2'] : NULL;
			$user->name = isset($_POST['name']) ? $_POST['name'] : NULL;
			$errors = $user->register();
			if (empty($errors))
			{
				$this->sendConfirmation($user);
				$message = 'Thank you for your registration<br>Check your email to confirm your registration';
				$this->view('account/confirmation', array('message' => $message))->render();
			}
			else
				$this->view('account/register', array('errors' => $errors))->render();
		}
		else
			$this->view('account/register')->render();
	}

	public function sendConfirmation(User $user)
	{
		$to = $user->email;
		$subject = 'Registration Confirmation';
		$headers = array(
			'From' => 'Admin Camagru <camagru.rizky@gmail.com>',
			'Reply-To' => 'Admin Camagru <camagru.rizky@gmail.com>',
			'MIME-Version' => '1.0',
			'Content-Type' => 'text/html; charset=UTF-8',
		);
		$message = $this->view('email/confirmation', array('user' => $user))->dump();
		mail($to, $subject, $message, $headers);
	}

	public function settings()
	{
		if ($this->user == NULL)
			$this->redirect('/account/login');
		if ($this->method === 'POST')
		{
			$user = new User;
			$user->id = isset($_POST['id']) ? $_POST['id'] : NULL;
			$user = User::get(array('id' => $user->id));
			if ($user == NULL)
				$this->redirect('/');
			$user->email = isset($_POST['email']) ? $_POST['email'] : $user->email;
			$user->name = isset($_POST['name']) ? $_POST['name'] : $user->name;
			$user->username = isset($_POST['username']) ? $_POST['username'] : $user->username;
			$user->subscribed = isset($_POST['subscribed']) && $_POST['subscribed'] ? true : false;
			$errors = $this->user->update($user);
			$password_old = isset($_POST['password_old']) ? $_POST['password_old'] : '';
			$user->password = isset($_POST['password']) ? $_POST['password'] : '';
			$user->password2 = isset($_POST['password2']) ? $_POST['password2'] : '';
			if ($password_old != '')
				$errors = $this->user->change_password($user, $password_old);
			if (empty($errors))
			{
				$this->user = $user;
				$_SESSION['user'] = serialize($this->user);
				$user = (array)$this->user;
				$user['checked'] = $this->user->subscribed ? 'checked' : '';
				$this->view('account/settings', array('user' => $user))->render();
			}
			else
				$this->view('account/settings', array('user' => $user, 'errors' => $errors))->render();
		}
		else
		{
			$user = (array)$this->user;
			$user['checked'] = $this->user->subscribed ? 'checked' : '';
			$this->view('account/settings', array('user' => $user))->render();
		}
	}

	public function logout()
	{
		unset($_SESSION['user']);
		$this->redirect('/');
	}

	public function recover()
	{
		if ($this->method === 'POST')
		{
			$user = new User;
			$user->email = isset($_POST['email']) ? $_POST['email'] : NULL;
			$user = User::get(array('email' => $user->email));
			if ($user)
			{
				$message = 'Password is being recovered check your email!';
				$user->tokenLost = $user->generateKey();
				$user->insert();
				$this->recoverPassword($user);
			}
			else
			{
				$message = 'Email address is not found';
			}
		}
		$this->view('password/recover', array('message' => $message))->render();
	}

	public function reset()
	{
		if ($this->method === 'POST' && User::validateTokenLost($_POST['key']))
		{
			$user = new User;
			$user->id = isset($_POST['id']) ? $_POST['id'] : NULL;
			$user = User::get(array('id' => $user->id));
			$user->password = isset($_POST['password']) ? $_POST['password'] : '';
			$user->password2 = isset($_POST['password2']) ? $_POST['password2'] : '';
			$user->tokenLost = $_POST['key'];
			$errors = $user->reset_password($user);
			if (empty($errors))
			{

				$this->view('account/login')->render();
			}
			else
			{
				$message = 'Type your new password';
				$this->view('password/reset', array('user' => $user, 'message' => $message, 'password' => true, 'errors' => $errors))->render();
			}
		}
		else if (isset($_GET['key']) && User::validateTokenLost($_GET['key']))
		{
			$message = 'Type your new password';
			$user = User::get(array('tokenLost' => $_GET['key']));
			$this->view('password/reset', array('user' => $user, 'message' => $message, 'password' => true))->render();
		}
		else
		{
			$message = 'Invalid key';
			$this->view('password/reset', array('message' => $message))->render();
		}
	}

	public function recoverPassword(User $user)
	{
		$to = $user->email;
		$subject = 'Password Reset';
		$headers = array(
			'From' => 'Admin Camagru <camagru.rizky@gmail.com>',
			'Reply-To' => 'Admin Camagru <camagru.rizky@gmail.com>',
			'MIME-Version' => '1.0',
			'Content-Type' => 'text/html; charset=UTF-8',
		);
		$message = $this->view('email/password', array('user' => $user))->dump();
		mail($to, $subject, $message, $headers);
	}
}
