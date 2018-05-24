<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '../', '../models', '../controllers')));
spl_autoload_register();

$admin = new User(array(
	'username' => 'admin',
	'password' => User::encrypt_password('admin', 'admin'),
	'name' => 'Admin',
	'email' => 'camagru.rizky@gmail.com')
);
$admin->insert();

$photo = new Photo(array(
	'createdAt' => '2018-05-22T06:46:39.000Z',
	'url' => 'https://media-cdn.tripadvisor.com/media/photo-s/10/3e/20/12/instagram-able-spot.jpg')
);
$admin->insert_photo($photo);
$admin->insert_comment($photo, 'cosmos cubic wall');

$photo = new Photo(array(
	'createdAt' => '2018-05-12T06:46:39.000Z',
	'url' => 'https://www.ngetren.co.id/wp-content/uploads/2018/01/Motel-Mexicola-seminyak-bali.jpg')
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
	'url' => 'https://thespaces.com/wp-content/uploads/2018/04/monochrome-cj-hendry-brooklyn-exhibition-colour-rooms-new-york-usa_3.jpg')
);
$user->insert_photo($photo);
$user->insert_comment($photo, 'pinky think thank toe');

$photo = new Photo(array(
	'createdAt' => '2018-02-22T06:46:39.000Z',
	'url' => 'https://i2.wp.com/www.dametraveler.com/wp-content/uploads/2017/09/ATL-spots.jpg')
);
$user->insert_photo($photo);
$user->insert_comment($photo, 'raining color');

$photo = new Photo(array(
	'createdAt' => '2018-05-22T06:46:39.000Z',
	'url' => 'http://timetravelblonde.com/wp-content/uploads/2017/03/Screenshot_2017-03-16-14-39-29-e1489737224349-836x1024.png')
);
$user->insert_photo($photo);
$user->insert_comment($photo, 'summer is malibu');
$admin->insert_comment($photo, 'nice pic!');
$user->insert_comment($photo, 'thanks!!');
$user->like($photo);

$photo = new Photo(array(
	'url' => 'https://www.piknikdong.com/wp-content/uploads/2017/12/Kampung-Pelangi-Semarang-Yang-Instagramable.jpg')
);
$user->insert_photo($photo);
$user->insert_comment($photo, 'stairway to the rainbow');
$admin->insert_comment($photo, 'nice pic!');
$admin->like($photo);
$user->like($photo);

echo "Done !<br><br>";
echo "<a href='/'>Back to Home</a>";