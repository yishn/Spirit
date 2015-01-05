<?php

class Spirit {
    public static function renderLogin() {
        $context = [ 
            'baseUrl' => Dispatcher::config('url')
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

            'hasPreviousPage' => $hasPreviousPage,
            'hasNextPage' => $hasNextPage,
            'hasPagination' => $hasPreviousPage || $hasNextPage
        ];

        return $context;
    }
}