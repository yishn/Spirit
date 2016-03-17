<?php

/**
 * Edit this file and save it under `config.php`.
 */

// Base path with trailing '/'
define('BASE_PATH', '/');

// Image size
define('IMG_SIZE', '1280x');

/**
 * Ok, stop editing now!
 */

$items = scandir('lib/');

foreach ($items as $item) {
    if (substr($item, -4) === '.php' && file_exists('lib/' . $item)) {
        require_once('lib/' . $item);
    }
}
