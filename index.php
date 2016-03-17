<?php

require_once('config.php');

spirit_route('GET', '/', function() { echo 'Hello World!'; });

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
