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
        if (isset($filter['album'])) $query = $filter['album']->photos();
        if (isset($filter['month'])) {
            try {
                $dateStart = new DateTime($filter['month'] . '-01');
                $dateEnd = new DateTime($filter['month'] . '-01');
                $dateEnd->add(new DateInterval('P1M'));

                $query = $query->where_gte('date', $dateStart->format('Y-m-d H:i:s'))
                    ->where_lt('date', $dateEnd->format('Y-m-d H:i:s'));
            } catch(Exception $ex) {
                Dispatcher::error(404);
                exit();
            }
        }

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