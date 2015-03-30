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

// The path to your content directory. Make sure it's writable!
define('CONTENTDIR', 'spirit-content/');

/**
 * Ok, stop editing now!
 */

// Configure Route
Route::config(array(
    'url' => BASEURL,
    'contentDir' => CONTENTDIR
));

// Configure Idiorm
ORM::configure(array(
    'connection_string' => 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
    'username' => DB_USER,
    'password' => DB_PASS,
    'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'),
    'caching' => true,
    'caching_auto_clear' => true
));

// Configure Paris
Model::$auto_prefix_tables = DB_PREFIX;