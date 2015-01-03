<?php

class Spirit {
    public static function setUpRoutes() {
        Dispatcher::map('GET', '/', function() { echo "Void."; });

        Dispatcher::map('GET', '/spirit/login', function() { 
            print Spirit::renderLogin();
        });

        Dispatcher::map('GET', '/spirit', function() {
            if (self::isLoggedIn()) Dispatcher::redirect('/spirit/photos');
            else Dispatcher::redirect('/spirit/login'); 
        });

        Dispatcher::map('GET', '/spirit/{page:photos|albums|users|settings}', function($params) {
            print Spirit::renderAdmin($params['page']);
        });
    }

    public static function isLoggedIn() {
        return false;
    }

    public static function renderLogin() {
        $context = [ 
            'css' => Dispatcher::link('/spirit/views/css/bootstrap.css')
        ];
        
        return Mustache::renderByFile('spirit/views/login', $context);
    }

    public static function renderAdmin($page) {
        $context = [
            'title' => 'Title',
            'css' => Dispatcher::link('/spirit/views/css/bootstrap.css'),
            'main' => self::renderAdminMain($page)
        ];

        return Mustache::renderByFile('spirit/views/admin', $context);
    }

    public static function renderAdminMain($page) {
        $context = [];

        return Mustache::renderByFile('spirit/views/page-' . $page, $context);
    }
}