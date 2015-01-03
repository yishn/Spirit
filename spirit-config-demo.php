<?php

/**
 * Edit this file and save it under `spirit-config.php`.
 */

// MySQL settings
define('DB_HOST', '');  
define('DB_USER', '');  
define('DB_PASS', '');  
define('DB_NAME', '');  
define('DB_PREFIX', '');

// The base url of your photoblog
define('URL', '');

/**
 * Ok, stop editing now!
 */

// Configure dispatch
Dispatcher::config(array('url' => URL));

// Configure Idiorm
ORM::configure(array(
    'connection_string' => 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
    'username' => DB_USER,
    'password' => DB_PASS,
    'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf_8')
));

// Configure Paris
Model::$auto_prefix_models = DB_PREFIX;