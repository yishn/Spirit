<?php

class Admin {
    public function __construct() {
        if ($this->isLoggedIn()) return;
        Route::redirect('/spirit/login');
    }

    public function isLoggedIn() {
        $this->user = User::where('email', 'shen.yichuan@gmail.com')->find_one();
        return true;
    }

    public function renderAdmin($main, $params = []) {
        $context = [
            'title' => Setting::get('title'),
            'baseUrl' => Route::config('url'),
            'user' => $this->user->as_array(),

            'mainPhotos' => $main == 'photos' || $main == 'photo-edit' || $main == 'upload',
            'mainAlbums' => $main == 'albums' || $main == 'album-edit',
            'mainUsers' => $main == 'users',
            'mainSettings' => $main == 'settings'
        ];
        $context['main'] = function() use($main, $context, $params) {
            return self::renderAdminMain($main, $context, $params);
        };

        return Mustache::renderByFile('spirit/views/admin', $context);
    }

    public function renderAdminMain($main, array $context, array $params = []) {
        if ($main == 'photos') {
            $context = array_merge($context, Photo::getPhotos(
                intval(Setting::get('adminPhotosPerPage')), $params['filter'], $params['page'])
            );
            $context['previousPageLink'] = Route::buildFilterRoute('spirit/photos', $params['filter'], $params['page'] - 1);
            $context['nextPageLink'] = Route::buildFilterRoute('spirit/photos', $params['filter'], $params['page'] + 1);
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
            $context['previousPageLink'] = Route::buildFilterRoute('spirit/albums', $params['filter'], $params['page'] - 1);
            $context['nextPageLink'] = Route::buildFilterRoute('spirit/albums', $params['filter'], $params['page'] + 1);
        } else if ($main == 'album-edit') {
            $album = $params['id'] == 'new' ? Album::create() : Album::find_one($params['id']);
            $context = array_merge($context, $album->as_array());
            $context['createNew'] = $params['id'] == 'new';
        }

        return Mustache::renderByFile('spirit/views/' . $main, $context);
    }

    public function executeAction($action, $params = []) {
        include("spirit/actions/{$action}.php");
    }
}