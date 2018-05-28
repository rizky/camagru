<?php
session_start();
function autoLoader($class) {
	(file_exists('app/' . $class . '.php')) ? include 'app/' . $class . '.php' : 0;
	(file_exists('app/models/' . $class . '.php')) ? include 'app/models/' . $class . '.php' : 0;
	(file_exists('app/controllers/' . $class . '.php')) ? include 'app/controllers/' . $class . '.php' : 0;
	(file_exists('config/' . $class . '.php')) ? include 'config/' . $class . '.php' : 0;
}

spl_autoload_register('autoLoader');
$app = new App;