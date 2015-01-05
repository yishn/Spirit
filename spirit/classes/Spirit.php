<?php

class Spirit {
    public static function renderLogin() {
        $context = [ 
            'baseUrl' => Route::config('url')
        ];
        
        return Mustache::renderByFile('spirit/views/login', $context);
    }

    public static function getPhotosContext($limit, array $filter = [], $page = 1) {
        $query = Model::factory('Photo');

        // Filter
        if (isset($filter['album'])) $query = $query->filter('in_album', $filter['album']);
        if (isset($filter['month'])) $query = $query->filter('in_month', $filter['month']);

        $photos = $query->order_by_desc('date')
            ->limit($limit + 1)
            ->offset(($page - 1) * $limit)
            ->find_many();

        $hasPreviousPage = $page != 1 && count($photos) != 0;
        $hasNextPage = count($photos) == $limit + 1;
        if ($hasNextPage) array_pop($photos);
        
        $photos = array_map(function($photo) {
            return $photo->as_array();
        }, $photos);

        $context = [
            'hasPhotos' => count($photos) != 0,
            'photos' => $photos,

            'hasFilters' => isset($filter['album']) || isset($filter['month']),
            'filterAlbum' => !isset($filter['album']) ? false : $filter['album']->as_array(),
            'filterMonth' => !isset($filter['month']) ? false : [
                'year' => substr($filter['month'], 0, 4),
                'month' => date('F', mktime(0, 0, 0, intval(substr($filter['month'], -2)), 1, 2000))
            ],

            'hasPagination' => $hasPreviousPage || $hasNextPage,
            'hasPreviousPage' => $hasPreviousPage,
            'hasNextPage' => $hasNextPage
        ];

        return $context;
    }
}