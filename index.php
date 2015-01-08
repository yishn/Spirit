<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once('spirit/includes.php');

Route::map();
Route::dispatch();

if (!ORM::get_config('logging')) return;

echo "\n<!--\n";
print_r(ORM::get_query_log());
echo "-->";