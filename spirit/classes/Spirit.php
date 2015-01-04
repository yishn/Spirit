<?php

class Spirit {
    public static function setUpRoutes() {
        Dispatcher::map('GET', '/', function() { echo "Void."; });

        Dispatcher::map('GET', '/photo/{id:\d+}', function($params) {
            Dispatcher::redirect('/photo/' . $params['id'] . '/size/large');
        });
        Dispatcher::map('GET', '/photo/{id:\d+}/size/{size:thumb|large}', function($params) {
            $photo = Photo::find_one($params['id']);
            if (!$photo) {
                Dispatcher::error(404);
                exit();
            }
            
            $size = Setting::where('key', $params['size'] == 'thumb' ? 'thumbSize' : 'largeImageSize')
                ->find_one()
                ->value;
            $photo->generateThumbnail($size, [ 'zoom' => $params['size'] == 'thumb' ]);
        });

        Dispatcher::map('GET', '/spirit/login', function() { 
            print self::renderLogin();
        });

        Dispatcher::map('GET', '/spirit', function() { Dispatcher::redirect('/spirit/photos'); });
        Dispatcher::map('GET', '/spirit/{main:photos|albums|users|settings}', function($params) {
            $admin = new SpiritAdmin();
            print $admin->renderAdmin($params['main']);
        });
        Dispatcher::map('GET', '/spirit/photos/filter/album/{id:\d+}', function($params) {
            $admin = new SpiritAdmin();
            $album = Album::find_one($params['id']);
            print $admin->renderAdmin('photos', [ 'album' => $album ]);
        });
    }

    public static function renderLogin() {
        $context = [ 
            'baseUrl' => Dispatcher::config('url')
        ];
        
        return Mustache::renderByFile('spirit/views/login', $context);
    }

    public static function getPhotosContext($limit, array $filter = [], $page = 1) {
        $query = Model::factory('Photo');
        if (isset($filter['album'])) $query = $filter['album']->photos();

        $photos = $query->order_by_desc('date')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->find_many();

        $photos = array_map(function($photo) {
            return $photo->as_array();
        }, $photos);

        $context = [
            'hasPhotos' => count($photos) != 0,
            'photos' => $photos
        ];

        return $context;
    }
}