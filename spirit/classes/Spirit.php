<?php

class Spirit {
    public static function renderLogin() {
        $context = [ 
            'css' => Dispatcher::link('/spirit/views/css/bootstrap.css')
        ];
        
        return Mustache::renderByFile('spirit/views/login', $context);
    }

    public static function renderAdmin($page) {
        $context = [
            'title' => 'Title',
            'css' => Dispatcher::link('/spirit/views/css/bootstrap.css'),
            'main' => self::renderAdminMain($page)
        ];

        return Mustache::renderByFile('spirit/views/admin', $context);
    }

    public static function renderAdminMain($page) {
        $context = [];

        return Mustache::renderByFile('spirit/views/page-' . $page, $context);
    }
}