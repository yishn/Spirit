<?php

class Spirit {
    public static function setUpRoutes() {
        Dispatcher::map('GET', '/', function() { echo "Void."; });

        Dispatcher::map('GET', '/photo/{id:\d*}', function($params) { echo "Photo ID " . $params['id']; });
        Dispatcher::map('GET', '/photo/{id:\d*}/thumb', function($params) {
            Spirit::generateThumbnail($params['id']);
        });

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

    public static function generateThumbnail($photoId) {
        $photo = Photo::find_one($photoId);
        $filename = $photo->filename;
        $contentDir = Setting::where('key', 'contentDir')->find_one()->value;
        $path = ABSURL . "/{$contentDir}photos/" . $filename;
        $size = Setting::where('key', 'thumbSize')->find_one()->value;

        Thumb::render($path, $size, 1);
    }
}