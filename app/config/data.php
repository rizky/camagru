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

$photo = new Photo(array(
	'user' => (array)$user,
	'url' => 'http://timetravelblonde.com/wp-content/uploads/2017/03/Screenshot_2017-03-16-14-39-29-e1489737224349-836x1024.png',
	'likes' => 100));
$photo->id = ORM::getInstance()->store('photo', get_object_vars($photo));

$photo = new Photo(array(
	'user' => (array)$user,
	'url' => 'https://www.piknikdong.com/wp-content/uploads/2017/12/Kampung-Pelangi-Semarang-Yang-Instagramable.jpg',
	'likes' => 100));
$photo->id = ORM::getInstance()->store('photo', get_object_vars($photo));

$photo = new Photo(array(
	'user' => (array)$user,
	'url' => 'https://www.ngetren.co.id/wp-content/uploads/2018/01/Motel-Mexicola-seminyak-bali.jpg',
	'likes' => 100));
$photo->id = ORM::getInstance()->store('photo', get_object_vars($photo));

$photo = new Photo(array(
	'user' => (array)$user,
	'url' => 'https://thespaces.com/wp-content/uploads/2018/04/monochrome-cj-hendry-brooklyn-exhibition-colour-rooms-new-york-usa_3.jpg',
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