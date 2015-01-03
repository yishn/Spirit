<?php

class Spirit {
    public static function setUpRoutes() {
        Dispatcher::map('GET', '/', function() { echo "Void."; });

        Dispatcher::map('GET', '/spirit/login', function() { 
            print self::renderLogin();
        });

        Dispatcher::map('GET', '/spirit', function() {
            Dispatcher::redirect('/spirit/photos');
        });

        Dispatcher::map('GET', '/spirit/{page:photos|albums|users|settings}', function($params) {
            $admin = new SpiritAdmin();
            print $admin->renderAdmin($params['page']);
        });
    }

    public static function renderLogin() {
        $context = [ 
            'baseUrl' => Dispatcher::config('url')
        ];
        
        return Mustache::renderByFile('spirit/views/login', $context);
    }
}