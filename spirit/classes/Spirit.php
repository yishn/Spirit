<?php

class Spirit {
    public static function renderLogin() {
        $context = [ 
            'baseUrl' => Route::config('url')
        ];
        
        return Mustache::renderByFile('spirit/views/login', $context);
    }
}