<?php

class SpiritAdmin {
    public function __construct() {
        if ($this->isLoggedIn()) return;
        Dispatcher::redirect('/spirit/login');
    }

    public function isLoggedIn() {
        $this->user = User::create();
        $this->user->name = "admin";
        return true;
    }

    public function renderAdmin($main, $params = []) {
        $context = [
            'title' => Setting::where('key', 'title')->find_one()->value,
            'baseUrl' => Dispatcher::config('url'),
            'main' => self::renderAdminMain($main, $params),
            'user' => $this->user->as_array(),

            'mainPhotos' => $main == 'photos',
            'mainAlbums' => $main == 'albums',
            'mainUsers' => $main == 'users',
            'mainSettings' => $main == 'settings'
        ];

        return Mustache::renderByFile('spirit/views/admin', $context);
    }

    public function renderAdminMain($main, array $params = []) {
        $context = [ 'baseUrl' => Dispatcher::config('url') ];

        if ($main == 'photos') {
            $context = array_merge($context, Spirit::getPhotosContext(3, $params));
            $context['filters'] = isset($params['month']) || isset($params['album']);
        }

        return Mustache::renderByFile('spirit/views/' . $main, $context);
    }
}