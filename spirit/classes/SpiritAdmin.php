<?php

class SpiritAdmin {
    public function __construct() {
        if ($this->isLoggedIn()) return;

        Dispatcher::redirect('/spirit/login');
    }

    public function isLoggedIn() {
        return true;
    }

    public function renderAdmin($page) {
        $context = [
            'title' => Setting::where('key', 'title')->find_one()->value,
            'baseUrl' => Dispatcher::config('url'),
            'main' => self::renderAdminMain($page),

            'pagePhotos' => $page == 'photos',
            'pageAlbums' => $page == 'albums',
            'pageUsers' => $page == 'users',
            'pageSettings' => $page == 'settings'
        ];

        return Mustache::renderByFile('spirit/views/admin', $context);
    }

    public function renderAdminMain($page) {
        $context = [
            'baseUrl' => Dispatcher::config('url')
        ];

        return Mustache::renderByFile('spirit/views/page-' . $page, $context);
    }
}