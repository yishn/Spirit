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
    $admin = new Admin();
    print $admin->renderAdmin($params['main']);
});

// Photos

$photosRoute = function($params) {
    $admin = new Admin();

    $temp = [ 'filter' => [] ];
    if (isset($params['album']))
        $temp['filter']['album'] = Album::find_one($params['album']);
    if (isset($params['year']) && isset($params['month']))
        $temp['filter']['month'] = $params['year'] . '-' . $params['month'];
    if (isset($params['page']))
        $temp['page'] = $params['page'] !== '' ? intval($params['page']) : 1;

    print $admin->renderAdmin('photos', $temp);
};

Dispatcher::map('GET', '/spirit/photos/{page:\d*}', $photosRoute);
Dispatcher::map('GET', '/spirit/photos/album/{album:\d+}/{page:\d*}', $photosRoute);
Dispatcher::map('GET', '/spirit/photos/{year:\d\d\d\d}-{month:\d\d}/{page:\d*}', $photosRoute);
//Dispatcher::map('GET', '/spirit/photos/{year:\d\d\d\d}-{month:\d\d}/album/{album:\d+}/{page:\d*}', $photosRoute);