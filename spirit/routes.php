<?php

/**
 * Theme routes
 */

Dispatcher::map('GET', '/', function() { echo "Void."; });
Dispatcher::map(404, function() { echo "Error 404"; });

Dispatcher::map('GET', '/photo/{id:\d+}', function($params) {
    Dispatcher::redirect('/photo/' . $params['id'] . '/size/large');
});
Dispatcher::map('GET', '/photo/{id:\d+}/size/{size:thumb|large}', function($params) {
    $photo = Photo::find_one($params['id']);
    if (!$photo) {
        Dispatcher::error(404);
        exit();
    }
    
    $size = Setting::where('key', $params['size'] == 'thumb' ? 'thumbSize' : 'largeImageSize')
        ->find_one()
        ->value;
    $photo->generateThumbnail($size, [ 'zoom' => $params['size'] == 'thumb' ]);
});

/**
 * Admin routes
 */

Dispatcher::map('GET', '/spirit/login', function() { 
    print self::renderLogin();
});

Dispatcher::map('GET', '/spirit', function() { 
    Dispatcher::redirect('/spirit/photos'); 
});
Dispatcher::map('GET', '/spirit/{main:albums|users|settings}', function($params) {
    $admin = new SpiritAdmin();
    print $admin->renderAdmin($params['main']);
});

// Photos

Dispatcher::map('GET', '/spirit/photos/{page:\d*}', function($params) {
    $admin = new SpiritAdmin();
    $page = $params['page'] !== '' ? intval($params['page']) : 1;
    print $admin->renderAdmin('photos', [ 'page' => $page ]);
});

Dispatcher::map('GET', '/spirit/photos/filter', function() {
    Dispatcher::redirect('/spirit/photos');
});

Dispatcher::map('GET', '/spirit/photos/filter/album/{id:\d+}', function($params) {
    $admin = new SpiritAdmin();
    $album = Album::find_one($params['id']);
    print $admin->renderAdmin('photos', [ 'album' => $album ]);
});

Dispatcher::map('GET', '/spirit/photos/filter/{year:\d\d\d\d}-{month:\d\d}', function($params) {
    $admin = new SpiritAdmin();
    $month = $params['year'] . '-' . $params['month'];
    print $admin->renderAdmin('photos', [ 'month' => $month ]);
});

Dispatcher::map('GET', '/spirit/photos/filter/{year:\d\d\d\d}-{month:\d\d}/album/{id:\d+}', function($params) {
    $admin = new SpiritAdmin();
    $album = Album::find_one($params['id']);
    $month = $params['year'] . '-' . $params['month'];
    print $admin->renderAdmin('photos', [ 'album' => $album, 'month' => $month ]);
});