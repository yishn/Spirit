<?php

require_once('config.php');

function confirm_journal_id(&$args) {
    $journal = spirit_get_journal($args['id']);

    if ($journal === null) redirect(BASE_PATH . 'error');

    $args['journal'] = $journal;
}

spirit_route('GET', '/', function() { echo 'Hello World!'; });

spirit_route('GET', '/photo/:id@[-a-zA-Z0-9]+/:filename', function($args) {
    $journal = spirit_get_journal($args['id']);
    $imagepath = 'content/' . (isset($journal['path']) ? basename($journal['path']) : '') . '/' . $args['filename'];

    if ($journal === null || !file_exists($imagepath))
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
