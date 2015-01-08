<?php

class Route extends Dispatcher {
    public static function map() {
        self::mapTheme();
        self::mapAdmin();
    }

    private static function mapTheme() {
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
    }

    private static function mapAdmin() {
        parent::map('GET', '/spirit/login', function() { 
            print parent::renderLogin();
        });

        parent::map('GET', '/spirit', function() { 
            parent::redirect('/spirit/photos'); 
        });
        parent::map('GET', '/spirit/{main:users|settings}', function($params) {
            $admin = new Admin();
            print $admin->renderAdmin($params['main']);
        });

        // Photos

        $photosRoute = function($params) {
            $admin = new Admin();

            $temp = [ 'filter' => [] ];
            if (isset($params['album'])) {
                $temp['filter']['album'] = Album::find_one($params['album']);
                if (!$temp['filter']['album']) {
                    self::error(404);
                    exit();
                }
            }
            if (isset($params['search']))
                $temp['filter']['search'] = $params['search'];
            if (isset($params['month']))
                $temp['filter']['month'] = $params['month'];
            if (isset($params['page']))
                $temp['page'] = $params['page'] !== '' ? intval($params['page']) : 1;

            print $admin->renderAdmin('photos', $temp);
        };

        parent::map('GET', '/spirit/photos/{page:\d*}', $photosRoute);
        parent::map('GET', '/spirit/photos/search/{search:.+}/{page:\d*}', $photosRoute);
        parent::map('GET', '/spirit/photos/album/{album:\d+}/{page:\d*}', $photosRoute);
        parent::map('GET', '/spirit/photos/{month:\d\d\d\d-\d\d}/{page:\d*}', $photosRoute);
        
        parent::map('GET', '/spirit/photos/edit/{id:\d+}', function($params) {
            $admin = new Admin();
            print $admin->renderAdmin('photo-edit', $params);
        });

        // Albums
        
        $albumsRoute = function($params) {
            $admin = new Admin();

            $temp = [ 'filter' => [] ];
            if (isset($params['search']))
                $temp['filter']['search'] = $params['search'];
            if (isset($params['month']))
                $temp['filter']['month'] = $params['month'];
            if (isset($params['page']))
                $temp['page'] = $params['page'] !== '' ? intval($params['page']) : 1;

            print $admin->renderAdmin('albums', $temp);
        };

        parent::map('GET', '/spirit/albums/{page:\d*}', $albumsRoute);
        parent::map('GET', '/spirit/albums/search/{search:.+}/{page:\d*}', $albumsRoute);
        parent::map('GET', '/spirit/albums/{month:\d\d\d\d-\d\d}/{page:\d*}', $albumsRoute);
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