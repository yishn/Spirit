<?php

class SpiritAdmin {
    public function __construct() {
        if ($this->isLoggedIn()) return;

        Dispatcher::redirect('/spirit/login');
    }

    public function isLoggedIn() {
        return true;
    }

    public function renderAdmin($main, $page = 1) {
        $context = [
            'title' => Setting::where('key', 'title')->find_one()->value,
            'baseUrl' => Dispatcher::config('url'),
            'main' => self::renderAdminMain($main, $page),

            'mainPhotos' => $main == 'photos',
            'mainAlbums' => $main == 'albums',
            'mainUsers' => $main == 'users',
            'mainSettings' => $main == 'settings'
        ];

        return Mustache::renderByFile('spirit/views/admin', $context);
    }

    public function renderAdminMain($main, $page = 1) {
        $context = [];

        if ($main == 'photos') $context = $this->getPhotosContext($page);

        return Mustache::renderByFile('spirit/views/' . $main, $context);
    }

    public function getPhotosContext($page = 1) {
        $context = [];
        $limit = intval(Setting::where('key', 'photosPerPage')->find_one()->value);

        $photos = Photo::order_by_desc('date')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->find_many();
        
        $context['photos'] = array_map(function($photo) {
            return $photo->as_array();
        }, $photos);

        return $context;
    }
}