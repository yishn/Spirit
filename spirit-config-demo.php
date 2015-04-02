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

// The path to your upload directory. Make sure it's writable!
define('DIR_CONTENT', 'spirit-content/photos/');
// The path to your themes directory.
define('DIR_THEMES', 'spirit-content/themes/');

/**
 * Ok, stop editing now!
 */

// Configure Spirit
Spirit::config([
    'url' => BASEURL
]);

// Configure Idiorm
ORM::configure([
    'connection_string' => 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
    'username' => DB_USER,
    'password' => DB_PASS,
    'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'),
    'caching' => true,
    'caching_auto_clear' => true
]);

// Configure Paris
Model::$auto_prefix_tables = DB_PREFIX;