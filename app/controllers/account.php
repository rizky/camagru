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
		if (User::get(array('username' => $username)) == NULL)
			$this->redirect('/');
		$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
		$photos = Photo::find(array('user' => $username), $offset);
		$more_v = count($photos) == 0 ? 'hidden' : 'show';
		$this->view = $this->view('photos/index', array('photos' => $photos, 'offset' => count($photos) + $offset, 'more_v' => $more_v));
		$this->view->render();
	}

	public function login()
	{
		if ($this->method === 'POST' && isset($_POST['username']) && $_POST['password'])
		{
			$user = User::Login($_POST['username'], $_POST['password']);
			if ($user instanceof User)
			{
				$_SESSION['user'] = serialize($user);
				$this->redirect('/');
			}
		}
		$this->view('account/login')->render();
	}

	public function conformation()
	{
		if (isset($_GET['key'])) {
            if(User::validateEmail($_GET['key'])) {
                $message = 'Your account has been validated';
            }else{
                $message = 'Invalid key';
            }
		}
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
				$this->sendEmail($user);
				$message = 'Thank you for your registration<br>Check your email to confirm your registration';
				$this->view('account/confirmation', array('message' => $message))->render();
			}
			else
				$this->view('account/register', array('errors' => $errors))->render();
		}
		else
			$this->view('account/register')->render();
	}

	public function sendEmail(User $user)
	{
		$to = $user->email;
		$subject = 'Registration Confirmation';
		$headers = array(
			'From' => 'Admin Camagru <camagru.rizky@gmail.com>',
			'Reply-To' => 'Admin Camagru <camagru.rizky@gmail.com>',
			'MIME-Version' => '1.0',
			'Content-Type' => 'text/html; charset=UTF-8',
		);
		$message = $this->view('account/email', array('user' => $user))->dump();
		mail($to, $subject, $message, $headers);
	}

	public function settings()
	{
		if ($this->user == NULL)
			$this->redirect('/account/login');
		$this->view('/account/settings', array('user' => (array)$this->user))->render();
	}

	public function logout()
	{
		unset($_SESSION['user']);
		$this->redirect('/');
	}
}
