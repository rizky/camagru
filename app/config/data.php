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

$photo = new Photo(array(
		'user' => (array)$user,
		'url' => 'https://media-cdn.tripadvisor.com/media/photo-s/10/3e/20/12/instagram-able-spot.jpg',
		'likes' => 200));
$photo->id = ORM::getInstance()->store('photo', get_object_vars($photo));

$photo = new Photo(array(
	'user' => (array)$user,
	'url' => 'https://i2.wp.com/www.dametraveler.com/wp-content/uploads/2017/09/ATL-spots.jpg',
	'likes' => 100));
$photo->id = ORM::getInstance()->store('photo', get_object_vars($photo));

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