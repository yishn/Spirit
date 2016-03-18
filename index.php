<?php

require_once('config.php');

function confirm_journal_id(&$args) {
    $journal = spirit_journals($args['id']);

    if ($journal === null) {
        render('view/error.phtml');
        exit();
    }

    $args['journal'] = $journal;
}

spirit_route('GET', '/', function() {
    $journals = spirit_journals();

    if (count($journals) === 0) render('view/error.phtml');
    else redirect($journals[0]['permalink']);
});

spirit_route('GET', '/:id@[-a-zA-Z0-9]+', [confirm_journal_id, function($args) {
    render('view/journal.phtml', [
        'journals' => spirit_journals(),
        'journal' => $args['journal']
    ]);
}]);

spirit_route('GET', '/photo/:id@[-a-zA-Z0-9]+/:filename', [confirm_journal_id, function($args) {
    $journal = $args['journal'];
    $imagepath = $journal['path'] . '/' . $args['filename'];

    if (!file_exists($imagepath)) render('view/error.phtml');
    else Thumb::render($imagepath, IMG_SIZE);
}]);

spirit_route('GET', '/download/:id@[-a-zA-Z0-9]+/:filename', [confirm_journal_id, function($args) {
    $journal = $args['journal'];
    $imagepath = $journal['path'] . '/' . $args['filename'];

    if (!file_exists($imagepath)) return render('view/error.phtml');

    header('Content-Type: application/octet-stream');
    header('Content-Length: ' . filesize($imagepath));
    header('Content-Disposition: attachment; filename=' . basename($imagepath));

    readfile($imagepath);
    exit();
}]);

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
