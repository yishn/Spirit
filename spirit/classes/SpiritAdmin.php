<?php

class SpiritAdmin {
    public function __construct() {
        if ($this->isLoggedIn()) return;

        Dispatcher::redirect('/spirit/login');
    }

    public function isLoggedIn() {
        return true;
    }

    public function renderAdmin($main) {
        $context = [
            'title' => Setting::where('key', 'title')->find_one()->value,
            'baseUrl' => Dispatcher::config('url'),
            'main' => self::renderAdminMain($main),

            'mainPhotos' => $main == 'photos',
            'mainAlbums' => $main == 'albums',
            'mainUsers' => $main == 'users',
            'mainSettings' => $main == 'settings'
        ];

        return Mustache::renderByFile('spirit/views/admin', $context);
    }

    public function renderAdminMain($main, $page = 1) {
        $context = [ 'baseUrl' => Dispatcher::config('url') ];

        if ($main == 'photos') {
            $limit = intval(Setting::where('key', 'photosPerPage')->find_one()->value);
            $photos = Photo::order_by_desc('date')
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->find_many();
            $context['photos'] = array_map(function($photo) {
                return $photo->as_array();
            }, $photos);
        }

        return Mustache::renderByFile('spirit/views/' . $main, $context);
    }
}