<?php

session_start();
require_once('spirit/includes.php');

map('GET', '/', function() {
    print Mustache::render("Hello {{name}}!", array('name' => 'Spirit'));
});

dispatch();