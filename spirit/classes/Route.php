<?php

class Route extends Dispatcher {
    public static function map() {
        self::mapTheme();
        self::mapAdmin();
        self::mapPartials();
    }

    private static function mapTheme() {
        parent::map('GET', '/', function() { echo "Void."; });
        parent::map(401, function() { echo "Error 401: Unauthorized"; });
        parent::map(404, function() { echo "Error 404: Not Found"; });

        parent::map('GET', '/photo/{id:\d+}', function($params) {
            parent::redirect('/photo/' . $params['id'] . '/size/large');
        });
        parent::map('GET', '/photo/{id:\d+}/size/{size:thumb|large}', function($params) {
            $photo = self::verifyModel('Photo', $params['id']);
            $size = Setting::get($params['size'] == 'thumb' ? 'thumbSize' : 'largeImageSize');
            $photo->generateThumbnail($size, [ 'zoom' => $params['size'] == 'thumb' ]);
        });
        parent::map('GET', '/photo/{id:\d+}/download', function($params) {
            if (Setting::get('originalPhotoDownload') != 'true')
                parent::error(404);

            $photo = self::verifyModel('Photo', $params['id']);
            $photo->download();
        });
    }

    private static function mapAdmin() {
        parent::map('GET', '/spirit', function() { parent::redirect('/spirit/photos'); });
        parent::map('GET', '/spirit/login', function() { echo 'Login'; });
        parent::map('GET', '/spirit/logout', function() {
            session_destroy();
            parent::redirect('/');
        });
        parent::map('GET', '/spirit/{main:users|settings|about}', function($params) {
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

        // Photos & Albums

        parent::map('GET', '/spirit/{main:photos|albums}/{page:\d*}', $prepareFilter);
        parent::map('GET', '/spirit/{main:photos|albums}/search/{search:.+}/{page:\d*}', $prepareFilter);
        parent::map('GET', '/spirit/{main:photos|albums}/{month:\d\d\d\d-\d\d}/{page:\d*}', $prepareFilter);
        parent::map('GET', '/spirit/{main:photos}/album/{album:\d+}/{page:\d*}', $prepareFilter);
        
        parent::map('GET', '/spirit/photos/upload', function() {
            $admin = new Admin();
            print $admin->renderAdmin('upload');
        });

        // Edit records

        parent::map('GET', '/spirit/photos/edit/{ids:(\d+,?)+}', function($params) {
            $admin = new Admin();
            $ids = self::verifyModels('Photo', $params['ids']);
            print $admin->renderAdmin('photo-edit', $ids);
        });
                
        parent::map('GET', '/spirit/albums/edit/{id:\d+|new}', function($params) {
            $admin = new Admin();
            if ($params['id'] != 'new') self::verifyModel('Album', $params['id']);
            print $admin->renderAdmin('album-edit', $params);
        });
                
        parent::map('GET', '/spirit/users/edit/{id:\d+|new}', function($params) {
            $admin = new Admin();
            if ($params['id'] != 'new') self::verifyModel('User', $params['id']);
            print $admin->renderAdmin('user-edit', $params);
        });

        // Actions

        parent::map('POST', '/spirit/photos/edit/{ids:(\d+,?)+}', function($params) {
            $admin = new Admin();
            $admin->executeAction('photo-edit', $params);
        });

        parent::map('POST', '/spirit/albums/edit/{id:\d+|new}', function($params) {
            $admin = new Admin();
            $admin->executeAction('album-edit', $params);
        });

        parent::map('POST', '/spirit/users/edit/{id:\d+|new}', function($params) {
            $admin = new Admin();
            $admin->executeAction('user-edit', $params);
        });

        parent::map('POST', '/spirit/photos/upload/{mode:(id)?}', function($params) {
            $admin = new Admin();
            $admin->executeAction('upload', $params);
        });

        parent::map('GET', '/spirit/photos/delete/{ids:(\d+,?)+}', function($params) {
            $admin = new Admin();
            $admin->executeAction('photo-delete', $params);
        });

        parent::map('GET', '/spirit/albums/delete/{id:\d+}', function($params) {
            $admin = new Admin();
            $admin->executeAction('album-delete', $params);
        });

        parent::map('GET', '/spirit/users/delete/{id:\d+}', function($params) {
            $admin = new Admin();
            $admin->executeAction('user-delete', $params);
        });
    }

    private static function mapPartials() {
        parent::map('GET', '/spirit/partial/albumpicker/{search:.*}', function($params) {
            $limit = intval(Setting::get('albumPickerItemsPerPage'));
            
            $context = Album::getAlbums($limit, [ 'search' => $params['search'] ]);
            $context['baseUrl'] = Route::config('url');

            print Mustache::renderByFile('spirit/views/partials/albumpicker', $context);
        });

        parent::map('GET', '/spirit/partial/monthpicker/{main:photos|albums}', function($params) {
            parent::redirect('/spirit/partial/monthpicker/' . $params['main'] . '/' . date('Y'));
        });
        parent::map('GET', '/spirit/partial/monthpicker/{main:photos|albums}/{year:\d\d\d\d}', function($params) {
            $context = [
                'months' => [],
                'baseUrl' => Route::config('url'),
                'previousYear' => str_pad(max($params['year'] - 1, 1), 4, '0', STR_PAD_LEFT),
                'nextYear' => str_pad(min($params['year'] + 1, 9999), 4, '0', STR_PAD_LEFT)
            ];
            $context = array_merge($context, $params);

            for ($i = 1; $i <= 12; $i++) {
                $id = $i < 10 ? '0' . $i : $i;
                $month = [
                    'id' => $id,
                    'name' => date('F', mktime(0, 0, 0, $i, 1, 2000)),
                    'activated' => Photo::filter('in_month', $params['year'] . '-' . $id)->count() != 0
                ];

                $context['months'][] = $month;
            }

            print Mustache::renderByFile('spirit/views/partials/monthpicker', $context);
        });
    }

    public static function verifyModel($model, $id) {
        $item = Model::factory($model)->where_id_is($id)->find_one();
        if (!$item) parent::error(404);

        return $item;
    }

    public static function verifyModels($model, $ids) {
        $array = explode(',', $ids);
        foreach ($array as $id) {
            self::verifyModel($model, $id);
        }

        return $array;
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