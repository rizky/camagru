<?php

set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '../', '../models', '../controllers')));
spl_autoload_register();

$user = new User;
$user->username = 'admin';
$user->password = User::encrypt_password('admin', 'admin');
$user->name = 'Admin';
$user->email = 'camagru.rizky@gmail.com';
$user->id = ORM::getInstance()->store('user', get_object_vars($user));

$user = new User;
$user->username = 'deleted_admin';
$user->password = User::encrypt_password('admin', 'admin');
$user->name = 'deleted_admin';
$user->email = 'deleted_admin@gmail.com';
$user->deleted = true;
$user->id = ORM::getInstance()->store('user', get_object_vars($user));


echo "Done !<br><br>";
echo "<a href='/'>Back to Home</a>";