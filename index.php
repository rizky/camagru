<?php
session_start();
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), './app', './app/models', './app/controllers')));
spl_autoload_register();

$app = new App;