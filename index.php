<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once('spirit/includes.php');
require_once('spirit/routes/routes.php');

Dispatcher::dispatch();