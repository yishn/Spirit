<?php

class Album extends Model {
    public function photos() {
        return $this->has_many_through('Photo');
    }

    public function getPhoto() {
        return $this->photos()
            ->order_by_desc('date')
            ->order_by_desc('id')
            ->find_one();
    }

    public function getPermalink() {
        return Spirit::link("/album/{$this->id}");
    }

    public function getFormattedDescription() {
        $parsedown = new Parsedown();
        return $parsedown->text($this->description);
    }

    public function getFormattedDate($format = 'Y-m-d H:i') {
        $photo = $this->getPhoto();
        $date = new DateTime(!$photo ? 'now' : $photo->date);
        return $date->format($format);
    }

    public function delete() {
        AlbumPhoto::where('album_id', $this->id)->delete_many();
        parent::delete();
    }

    public function as_array() {
        $result = parent::as_array();
        $result = array_merge($result, [
            'chronological' => $result['chronological'] == 1,
            'thumbnailLink' => function() {
                $photo = $this->getPhoto();
                return !$photo ? Spirit::link('/') : $photo->getThumbnailLink();
            },
            'largeImageLink' => function() {
                $photo = $this->getPhoto();
                return !$photo ? Spirit::link('/') : $photo->getLargeImageLink();
            },
            'permalink' => $this->getPermalink(),
            'count' => function() { return $this->photos()->count(); },
            'date' => function() { return $this->getFormattedDate(); },
            'month' => function() { return $this->getFormattedDate('Y-m'); },
            'formattedDescription' => function() { return $this->getFormattedDescription(); },
            'formattedDate' => function() { return $this->getFormattedDate(Setting::get('albumDateFormat')); }
        ]);

        return $result;
    }

    public static function create() {
        $album = parent::factory('Album')->create();
        $album->set([
            'id' => 'new',
            'name' => '',
            'description' => '',
            'chronological' => 0
        ]);
        return $album;
    }

    public static function getAlbums($limit, array $filter = [], $page = 1, $order = 'id') {
        $photoTable = DB_PREFIX . 'photo';
        $albumTable = DB_PREFIX . 'album';
        $table = DB_PREFIX . 'album_photo';

        $query = Album::select("{$albumTable}.*");
        if (isset($filter['search'])) $query = $query->filter('search', $filter['search']);
        if (isset($filter['month'])) $query = $query->filter('in_month', $filter['month']);

        if ($order == 'date') $query = $query->order_by_desc("{$photoTable}.date");
        else $query = $query->order_by_desc("{$albumTable}.id");

        $albums = $query->left_outer_join($table, ["{$table}.album_id", '=', "{$albumTable}.id"])
            ->left_outer_join($photoTable, ["{$photoTable}.id", '=', "{$table}.photo_id"])
            ->distinct()
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

            'hasFilters' => isset($filter['search']) || isset($filter['month']),
            'filterSearch' => !isset($filter['search']) ? false : [ 'search' => $filter['search'] ],
            'filterMonth' => !isset($filter['month']) ? false : [
                'year' => substr($filter['month'], 0, 4),
                'month' => date('F', mktime(0, 0, 0, intval(substr($filter['month'], -2)), 1, 2000))
            ],

            'hasPagination' => $hasPreviousPage || $hasNextPage,
            'hasPreviousPage' => $hasPreviousPage,
            'hasNextPage' => $hasNextPage
        ];
    }


    public static function search($orm, $input) {
        $albumTable = DB_PREFIX . 'album';
        $terms = array_filter(explode(' ', $input));

        foreach ($terms as $term) {
            $orm = $orm->where_any_is([
                [ "{$albumTable}.name" => "%{$term}%" ],
                [ "{$albumTable}.description" => "%{$term}%" ]
            ], 'LIKE');
        }

        return $orm;
    }

    public static function in_month($orm, $month) {
        return Photo::in_month($orm, $month);
    }
}