<?php

set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '../', '../models', '../controllers')));
spl_autoload_register();

$user = new User(
		array(
			'username' => 'admin',
			'password' => User::encrypt_password('admin', 'admin'),
			'name' => 'Admin',
			'email' => 'camagru.rizky@gmail.com'
		)
	);
$user->id = ORM::getInstance()->store('user', get_object_vars($user));

$user = new User(
	array(
		'username' => 'deleted_admin',
		'password' => User::encrypt_password('deleted_admin', 'admin'),
		'name' => 'Admin',
		'email' => 'deleted_admin@gmail.com'
	)
);
$user->id = ORM::getInstance()->store('user', get_object_vars($user));
$user->delete();

echo "Done !<br><br>";
echo "<a href='/'>Back to Home</a>";