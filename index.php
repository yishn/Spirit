<?php

require_once('config.php');

spirit_route('GET', '/', function() { echo 'Hello World!'; });

spirit_route('*', '*', function() { render('view/error.phtml'); });

dispatch();
