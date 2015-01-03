<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once('spirit/includes.php');

Spirit::setUpRoutes();
Dispatcher::dispatch();