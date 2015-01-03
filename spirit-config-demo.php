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

// The base url of your photoblog. Please add trailing `/`.
define('BASEURL', '/');

/**
 * Ok, stop editing now!
 */

// Configure Dispatcher
Dispatcher::config(array('url' => BASEURL));

// Configure Idiorm
ORM::configure(array(
    'connection_string' => 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
    'username' => DB_USER,
    'password' => DB_PASS,
    'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
));

// Configue Thumb
Thumb::$thumb_cache = ABSURL . '/cache/';