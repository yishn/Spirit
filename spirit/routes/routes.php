<?php

Dispatcher::map('GET', '/', function() { echo "Void."; });

Dispatcher::map('GET', '/spirit/login', function() { 
    print Spirit::renderLogin();
});

Dispatcher::map('GET', '/spirit', function() { 
    Dispatcher::redirect('/spirit/login'); 
});

Dispatcher::map('GET', '/spirit/{page:(photos)}', function($params) {
    print Spirit::renderAdmin($params['page']);
});