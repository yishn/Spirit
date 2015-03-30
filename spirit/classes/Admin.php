<?php

class Admin {
    public function __construct() {
        if ($this->isLoggedIn()) return;
        Route::redirect('/spirit/login');
    }

    public function isLoggedIn() {
        $this->user = User::where_id_is(Route::session('user'))->find_one();
        return $this->user !== false;
    }

    public function renderAdmin($main, $params = []) {
        $context = [
            'title' => Setting::get('title'),
            'baseUrl' => Route::config('url'),
            'user' => $this->user->as_array(),
            'root' => $this->user->root == 1,

            'mainLogin' => false,
            'mainPhotos' => $main == 'photos' || $main == 'photo-edit' || $main == 'upload',
            'mainAlbums' => $main == 'albums' || $main == 'album-edit',
            'mainUsers' => $main == 'users',
            'mainSettings' => $main == 'settings'
        ];
        $context['main'] = self::renderAdminMain($main, $context, $params);

        return Mustache::renderByFile('spirit/views/admin', $context);
    }

    public function renderAdminMain($main, array $context, array $params = []) {
        if ($main == 'photos') {
            $context = array_merge($context, Photo::getPhotos(
                intval(Setting::get('adminPhotosPerPage')), $params['filter'], $params['page'])
            );
            $context['previousPageLink'] = Route::buildFilterRoute('/spirit/photos', $params['filter'], $params['page'] - 1);
            $context['nextPageLink'] = Route::buildFilterRoute('/spirit/photos', $params['filter'], $params['page'] + 1);
        } else if ($main == 'photo-edit') {
            $ids = $params;
            $context['ids'] = implode(',', $ids);
            $context['photos'] = [];

            foreach ($ids as $id) {
                $context['photos'][] = Photo::find_one($id)->as_array();
            }

            $context['isSingle'] = count($context['photos']) == 1;
        } else if ($main == 'albums') {
            $context = array_merge($context, Album::getAlbums(
                intval(Setting::get('adminAlbumsPerPage')), $params['filter'], $params['page'])
            );
            $context['previousPageLink'] = Route::buildFilterRoute('/spirit/albums', $params['filter'], $params['page'] - 1);
            $context['nextPageLink'] = Route::buildFilterRoute('/spirit/albums', $params['filter'], $params['page'] + 1);
        } else if ($main == 'album-edit') {
            $album = $params['id'] == 'new' ? Album::create() : Album::find_one($params['id']);
            $context = array_merge($context, $album->as_array());
            $context['createNew'] = $album->is_new();
        } else if ($main == 'users') {
            $context = array_merge($context, User::getUsers());

            for ($i = 0; $i < count($context['users']); $i++) {
                $context['users'][$i]['current'] = $context['users'][$i]['id'] == $this->user->id;
            }
        } else if ($main == 'user-edit') {
            $user = $params['id'] == 'new' ? User::create() : User::find_one($params['id']);
            $context = array_merge($context, $user->as_array());
            $context['createNew'] = $user->is_new();
            $context['editable'] = $user->is_new() || $user->id == $this->user->id || $this->user->root;
        } else if ($main == 'settings' || $main == 'about') {
            $context = array_merge($context, Setting::as_array());

            $context['originalPhotoDownload'] = $context['originalPhotoDownload'] == 'true';
            $context['standard'] = Setting::$standards;
            $context['timezones'] = [];
            $lastcontinent = '';

            // Build timezones data
            foreach (timezone_identifiers_list() as $key) {
                $continent = substr($key, 0, strpos($key, '/'));
                if ($continent == '') $continent = 'Other';

                if ($lastcontinent != $continent) {
                    $context['timezones'][] = [ 'continent' => $continent, 'cities' => [] ];
                    $lastcontinent = $continent;
                }

                $context['timezones'][count($context['timezones']) - 1]['cities'][] = [
                    'key' => $key,
                    'name' => strtr($key, [
                        $continent . '/' => '',
                        'St_' => 'St. ',
                        '_' => ' ',
                        '/' => ' - '
                    ]),
                    'selected' => Setting::get('timezone') == $key
                ];
            }
        }

        return Mustache::renderByFile('spirit/views/' . $main, $context);
    }

    public function executeAction($action, $params = []) {
        include("spirit/actions/{$action}.php");
    }
}