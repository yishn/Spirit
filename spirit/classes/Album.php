<?php

class Album extends Model {
    public function photos() {
        return $this->has_many_through('Photo');
    }

    public function getPhoto() {
        return $this->photos()
            ->order_by_desc('date')
            ->limit(1)
            ->find_one();
    }

    public static function search($orm, $input) {
        return $orm->where_any_is([
            [ 'name' => "%{$input}%" ],
            [ 'description' => "%{$input}%" ]
        ], 'LIKE');
    }

    public static function getAlbums($limit, array $filter = [], $page = 1) {
        $photoTable = DB_PREFIX . 'photo';
        $albumTable = DB_PREFIX . 'album';
        $table = DB_PREFIX . 'album_photo';

        $query = Album::select("{$albumTable}.*");
        if (isset($filter['search'])) $query = $query->filter('search', $filter['search']);

        $albums = $query->join($table, array("{$table}.album_id", '=', "{$albumTable}.id"))
            ->join($photoTable, array("{$photoTable}.id", '=', "{$table}.photo_id"))
            ->order_by_desc("{$photoTable}.date")
            ->limit($limit + 1)
            ->offset(($page - 1) * $limit)
            ->find_many();

        $hasPreviousPage = $page != 1 && count($albums) != 0;
        $hasNextPage = count($albums) == $limit + 1;
        if ($hasNextPage) array_pop($albums);
        
        $albums = array_map(function($album) {
            return $album->as_array();
        }, $albums);

        return [
            'hasAlbums' => count($albums) != 0,
            'albums' => $albums,

            'hasFilters' => isset($filter['search']),
            'filterSearch' => !isset($fitler['search']) ? false : $filter['search'],

            'hasPagination' => $hasPreviousPage || $hasNextPage,
            'hasPreviousPage' => $hasPreviousPage,
            'hasNextPage' => $hasNextPage
        ];
    }
}