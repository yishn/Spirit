<?php

require_once('config.php');

function serve_json() {
    header('Content-Type: application/json');
}

spirit_route('GET', '/', function() { echo 'Hello World!'; });

/**
 * REST API
 */

spirit_route('GET', '/json/journals', [serve_json, function() {
    echo json_encode(spirit_journals());
}]);

spirit_route('GET', '/json/journals/:id@[-a-zA-Z0-9]+', [serve_json, function($args) {
    echo json_encode(spirit_journals($args['id']));
}]);

/**
 * Error
 */

spirit_route('*', '*', function() { render('view/error.phtml'); });

dispatch();
