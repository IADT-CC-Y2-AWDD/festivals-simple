<?php
// define some constants for use through the application
define('APP_URL', 'http://localhost/IADT-CC-Y2/festivals-simple');

define('DB_SERVER', 'localhost');
define('DB_DATABASE', 'festivals');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

define('KEY_EXCEPTION', '__EXCEPTION__');

// add the directory containing this configuration file to the PHP include path
// this ensures that the files we require/include can be found
set_include_path(
  get_include_path() . PATH_SEPARATOR . dirname(__FILE__)
);

// define an autoload function so that we can use classes without having to require them
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.php';
});

// load all our global function definitions
require_once "lib/global.php";

// start/retrieve the session for the user
session_start();
?>
