<?php

class Spirit {
    public static function setUpRoutes() {
        Dispatcher::map('GET', '/', function() { echo "Void."; });

        Dispatcher::map('GET', '/photo/{id:\d+}/size/{size:thumb|large}', function($params) {
            $photo = Photo::find_one($params['id']);
            if (!$photo) {
                Dispatcher::error(404);
                exit();
            }
            
            $size = Setting::where('key', $params['size'] == 'thumb' ? 'thumbSize' : 'largeImageSize')
                ->find_one()
                ->value;
            $photo->generateThumbnail($size, true);
        });

        Dispatcher::map('GET', '/spirit/login', function() { 
            print self::renderLogin();
        });

        Dispatcher::map('GET', '/spirit', function() { Dispatcher::redirect('/spirit/photos'); });
        Dispatcher::map('GET', '/spirit/{main:photos|albums|users|settings}', function($params) {
            $admin = new SpiritAdmin();
            print $admin->renderAdmin($params['main']);
        });
        Dispatcher::map('GET', '/spirit/{main:photos|albums|users|settings}/page/{page:\d+}', function($params) {
            $admin = new SpiritAdmin();
            print $admin->renderAdmin($params['main'], $params['page']);
        });
    }

    public static function renderLogin() {
        $context = [ 
            'baseUrl' => Dispatcher::config('url')
        ];
        
        return Mustache::renderByFile('spirit/views/login', $context);
    }
}