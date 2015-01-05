<?php

class Admin {
    public function __construct() {
        if ($this->isLoggedIn()) return;
        Route::redirect('/spirit/login');
    }

    public function isLoggedIn() {
        $this->user = User::create();
        $this->user->name = "admin";
        $this->user->id = 1;
        return true;
    }

    public function renderAdmin($main, $params = []) {
        $context = [
            'title' => Setting::where('key', 'title')->find_one()->value,
            'baseUrl' => Route::config('url'),
            'user' => $this->user->as_array(),

            'mainPhotos' => $main == 'photos',
            'mainAlbums' => $main == 'albums',
            'mainUsers' => $main == 'users',
            'mainSettings' => $main == 'settings'
        ];
        $context['main'] = function() use($main, $context, $params) {
            return self::renderAdminMain($main, $context, $params);
        };

        return Mustache::renderByFile('spirit/views/admin', $context);
    }

    public function renderAdminMain($main, array $context, array $params = []) {
        $limit = intval(Setting::where('key', 'adminPhotosPerPage')->find_one()->value);

        if ($main == 'photos') {
            $context = array_merge($context, Spirit::getPhotosContext($limit, $params['filter'], $params['page']));
            $context['previousPageLink'] = Route::buildAdminPhotosRoute($params, $params['page'] - 1);
            $context['nextPageLink'] = Route::buildAdminPhotosRoute($params, $params['page'] + 1);
        }

        return Mustache::renderByFile('spirit/views/' . $main, $context);
    }
}