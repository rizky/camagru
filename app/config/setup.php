<?php

class setup extends Controller
{
	protected $view;

	public function index()
	{
		$message = "Remove all data and recreate database!";
		$this->view('setup/index', array('message' => $message, 'next' => '/setup/db'))->render();
	}

	public function db()
	{
		require_once('database.php');
		$DB_DSN = 'mysql:host=db';
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$sql = file_get_contents('app/config/table.sql');
		$qr = $db->exec($sql);
		$message = "Populate Data";
		$this->view('setup/index', array('message' => $message, 'next' => '/setup/data'))->render();
	}

	public function data()
	{
		$admin = new User(array(
			'username' => 'admin',
			'password' => User::encrypt_password('admin', 'admin'),
			'name' => 'Admin',
			'email' => 'camagru.rizky@gmail.com')
		);
		$admin->insert();
		
		$photo = new Photo(array(
			'createdAt' => '2018-05-22T06:46:39.000Z',
			'url' => 'https://user-images.githubusercontent.com/6814254/40547073-32d77e60-6031-11e8-8f8c-5e9429224498.jpg')
		);
		$admin->insert_photo($photo);
		$admin->insert_comment($photo, 'cosmos cubic wall');
		
		$photo = new Photo(array(
			'createdAt' => '2018-05-12T06:46:39.000Z',
			'url' => 'https://user-images.githubusercontent.com/6814254/40547072-32b917cc-6031-11e8-8aa3-07d0353793ee.jpg')
		);
		$admin->insert_photo($photo);
		
		$deleted_admin = new User(array(
			'username' => 'deleted_admin',
			'password' => User::encrypt_password('deleted_admin', 'admin'),
			'name' => 'Admin',
			'email' => 'deleted_admin@gmail.com')
		);
		$deleted_admin->insert();
		$deleted_admin->delete();
		
		$user = new User(array(
			'username' => 'rizkyario',
			'password' => User::encrypt_password('rizkyario', 'admin'),
			'name' => 'Rizky Ario',
			'email' => 'rizkyario@gmail.com')
		);
		$user->insert();
		
		$photo = new Photo(array(
			'createdAt' => '2018-05-22T06:46:39.000Z',
			'url' => 'https://user-images.githubusercontent.com/6814254/40547069-31bb4e8a-6031-11e8-88f7-ec9480b235e7.jpg')
		);
		$user->insert_photo($photo);
		$user->insert_comment($photo, 'pinky think thank toe');
		
		$photo = new Photo(array(
			'createdAt' => '2018-02-22T06:46:39.000Z',
			'url' => 'https://user-images.githubusercontent.com/6814254/40547068-31a14026-6031-11e8-9095-845f6600dd9b.jpg')
		);
		$user->insert_photo($photo);
		$user->insert_comment($photo, 'raining color');
		
		$photo = new Photo(array(
			'createdAt' => '2018-05-22T06:46:39.000Z',
			'url' => 'https://user-images.githubusercontent.com/6814254/40547074-32f1a132-6031-11e8-9bbd-e8f9f389bca2.png')
		);
		$user->insert_photo($photo);
		$user->insert_comment($photo, 'summer is malibu');
		$admin->insert_comment($photo, 'nice pic!');
		$user->insert_comment($photo, 'thanks!!');
		$user->like($photo);
		
		$photo = new Photo(array(
			'url' => 'https://user-images.githubusercontent.com/6814254/40547075-330989d2-6031-11e8-9c0c-e7d690c70531.jpg')
		);
		$user->insert_photo($photo);
		$user->insert_comment($photo, 'stairway to the rainbow');
		$admin->insert_comment($photo, 'nice pic!');
		$admin->like($photo);
		$user->like($photo);
		$message = "Setup Complete!<br>Click Next to go to Homepage";
		$this->view('setup/index', array('message' => $message, 'next' => '/'))->render();
	}
}
