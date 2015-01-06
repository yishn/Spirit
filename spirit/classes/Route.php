<?php

class Route extends Dispatcher {
    public static function map() {
        /**
         * Theme routes
         */

        parent::map('GET', '/', function() { echo "Void."; });
        parent::map(404, function() { echo "Error 404"; });

        parent::map('GET', '/photo/{id:\d+}', function($params) {
            parent::redirect('/photo/' . $params['id'] . '/size/large');
        });
        parent::map('GET', '/photo/{id:\d+}/size/{size:thumb|large}', function($params) {
            $photo = Photo::find_one($params['id']);
            if (!$photo) {
                parent::error(404);
                exit();
            }
            
            $size = Setting::get($params['size'] == 'thumb' ? 'thumbSize' : 'largeImageSize');
            $photo->generateThumbnail($size, [ 'zoom' => $params['size'] == 'thumb' ]);
        });

        /**
         * Admin routes
         */

        parent::map('GET', '/spirit/login', function() { 
            print parent::renderLogin();
        });

        parent::map('GET', '/spirit', function() { 
            parent::redirect('/spirit/photos'); 
        });
        parent::map('GET', '/spirit/{main:albums|users|settings}', function($params) {
            $admin = new Admin();
            print $admin->renderAdmin($params['main']);
        });

        // Photos

        $photosRoute = function($params) {
            $admin = new Admin();

            $temp = [ 'filter' => [] ];
            if (isset($params['album']))
                $temp['filter']['album'] = Album::find_one($params['album']);
            if (isset($params['year']) && isset($params['month']))
                $temp['filter']['month'] = $params['year'] . '-' . $params['month'];
            if (isset($params['page']))
                $temp['page'] = $params['page'] !== '' ? intval($params['page']) : 1;

            print $admin->renderAdmin('photos', $temp);
        };

        parent::map('GET', '/spirit/photos/{page:\d*}', $photosRoute);
        parent::map('GET', '/spirit/photos/album/{album:\d+}/{page:\d*}', $photosRoute);
        parent::map('GET', '/spirit/photos/{year:\d\d\d\d}-{month:\d\d}/{page:\d*}', $photosRoute);
        //parent::map('GET', '/spirit/photos/{year:\d\d\d\d}-{month:\d\d}/album/{album:\d+}/{page:\d*}', $photosRoute);
    }

    public static function buildAdminPhotosRoute(array $filter = [], $page = 1) {
        $result = parent::config('url') . 'spirit/photos';

        if (isset($filter['month']))
            $result .= '/' . $filter['month'];
        if (isset($filter['album']))
            $result .= '/album/' . $filter['album']->id;
        if ($page != 1)
            $result .= "/{$page}";

        return $result;
    }
}