<?php

class Route extends Dispatcher {
    public static function map() {
        self::mapTheme();
        self::mapAdmin();
        self::mapPartials();
    }

    private static function mapTheme() {
        parent::map('GET', '/', function() { echo "Void."; });
        parent::map(404, function() { echo "Error 404"; });

        parent::map('GET', '/photo/{id:\d+}', function($params) {
            parent::redirect('/photo/' . $params['id'] . '/size/large');
        });
        parent::map('GET', '/photo/{id:\d+}/size/{size:thumb|large}', function($params) {
            $photo = self::verifyModel('Photo', $params['id']);
            $size = Setting::get($params['size'] == 'thumb' ? 'thumbSize' : 'largeImageSize');
            $photo->generateThumbnail($size, [ 'zoom' => $params['size'] == 'thumb' ]);
        });
    }

    private static function mapAdmin() {
        parent::map('GET', '/spirit', function() { parent::redirect('/spirit/photos'); });
        parent::map('GET', '/spirit/{main:users|settings}', function($params) {
            $admin = new Admin();
            print $admin->renderAdmin($params['main']);
        });

        $prepareFilter = function($params) {
            $admin = new Admin();
            $temp = [ 'filter' => [] ];

            if (isset($params['album']))
                $temp['filter']['album'] = self::verifyModel('Album', $params['album']);
            if (isset($params['search']))
                $temp['filter']['search'] = $params['search'];
            if (isset($params['month']))
                $temp['filter']['month'] = $params['month'];
            if (isset($params['page']))
                $temp['page'] = $params['page'] !== '' ? intval($params['page']) : 1;

            print $admin->renderAdmin($params['main'], $temp);
        };

        // Photos

        parent::map('GET', '/spirit/{main:photos}/{page:\d*}', $prepareFilter);
        parent::map('GET', '/spirit/{main:photos}/search/{search:.+}/{page:\d*}', $prepareFilter);
        parent::map('GET', '/spirit/{main:photos}/album/{album:\d+}/{page:\d*}', $prepareFilter);
        parent::map('GET', '/spirit/{main:photos}/{month:\d\d\d\d-\d\d}/{page:\d*}', $prepareFilter);
        
        parent::map('GET', '/spirit/photos/edit/{id:\d+}', function($params) {
            $admin = new Admin();
            self::verifyModel('Photo', $params['id']);
            print $admin->renderAdmin('photo-edit', $params);
        });

        parent::map('POST', '/spirit/photos/edit/{id:\d+}', function($params) {
            $admin = new Admin();
            $admin->executeAction('photo-edit', $params);
        });

        // Albums

        parent::map('GET', '/spirit/{main:albums}/{page:\d*}', $prepareFilter);
        parent::map('GET', '/spirit/{main:albums}/search/{search:.+}/{page:\d*}', $prepareFilter);
        parent::map('GET', '/spirit/{main:albums}/{month:\d\d\d\d-\d\d}/{page:\d*}', $prepareFilter);
        
        parent::map('GET', '/spirit/albums/edit/{id:\d+|new}', function($params) {
            $admin = new Admin();
            if ($params['id'] != 'new') self::verifyModel('Album', $params['id']);
            print $admin->renderAdmin('album-edit', $params);
        });

        parent::map('POST', '/spirit/albums/edit/{id:\d+|new}', function($params) {
            $admin = new Admin();
            $admin->executeAction('album-edit', $params);
        });

        // Actions

        parent::map('GET', '/spirit/{main:photos|albums}/delete/{id:\d+}', function($params) {
            $admin = new Admin();
            $album = self::verifyModel($params['main'] == 'albums' ? 'Album' : 'Photo', $params['id']);
            $album->delete();
            self::redirect('/spirit/' . $params['main']);
        });
    }

    private static function mapPartials() {
        parent::map('GET', '/spirit/partial/albums/{search:.*}', function($params) {
            $limit = intval(Setting::get('albumPickerItemsPerPage'));
            
            $context = Album::getAlbums($limit, [ 'search' => $params['search'] ]);
            $context['baseUrl'] = Route::config('url');

            print Mustache::renderByFile('spirit/views/partials/albums', $context);
        });
    }

    public static function verifyModel($model, $id) {
        $item = Model::factory($model)->find_one($id);
        if (!$item) {
            parent::error(404);
            exit();
        }

        return $item;
    }

    public static function buildFilterRoute($base, array $filter = [], $page = 1) {
        $result = parent::config('url') . $base;

        if (isset($filter['album']))
            $result .= '/album/' . $filter['album']->id;
        if (isset($filter['month']))
            $result .= '/' . $filter['month'];
        if (isset($filter['search']))
            $result .= '/search/' . urlencode($filter['search']);
        if ($page != 1)
            $result .= "/{$page}";

        return $result;
    }
}