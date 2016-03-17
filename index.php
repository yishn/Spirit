<?php

require_once('config.php');

function serve_json() {
    header('Content-Type: application/json');
}

spirit_route('GET', '/', function() { echo 'Hello World!'; });

spirit_route('GET', '/json/journals', [serve_json, function() {
    echo json_encode(spirit_journals());
}]);

spirit_route('*', '*', function() { render('view/error.phtml'); });

dispatch();
