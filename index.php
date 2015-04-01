<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('spirit/includes.php');

session_set_cookie_params(604800, Spirit::config('url'));
session_start();

Spirit::map();
Spirit::dispatch();

if (!ORM::get_config('logging')) return;

echo "\n<!--\n";
print_r(ORM::get_query_log());
echo "-->";