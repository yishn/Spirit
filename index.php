<?php

require_once('config.php');

spirit_route('GET', '/', function() { echo 'Hello World!'; });

spirit_route('GET', '/photo/:id@[-a-zA-Z0-9]+/:filename', function($args) {
    $path = spirit_get_journal_path($args['id']);
    $imagepath = 'content/' . basename($path) . '/' . $args['filename'];

    if ($path === null || !file_exists($imagepath))
        redirect(BASE_PATH . 'error');

    Thumb::render($imagepath, IMG_SIZE);
});

/**
 * REST API
 */

spirit_route('GET', '/json/journals', function() {
    spirit_json(spirit_journals());
});

spirit_route('GET', '/json/journals/:id@[-a-zA-Z0-9]+', function($args) {
    spirit_json(spirit_journals($args['id']));
});

/**
 * Error
 */

spirit_route('*', '*', function() { render('view/error.phtml'); });

dispatch();
