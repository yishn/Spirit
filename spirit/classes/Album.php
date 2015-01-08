<?php

class Album extends Model {
    public function photos() {
        return $this->has_many_through('Photo');
    }

    public function getPhoto() {
        return $this->photos()
            ->order_by_desc('date')
            ->find_one();
    }

    public function getFormattedDate($format = 'Y-m-d H:i') {
        $photo = $this->getPhoto();
        $date = new DateTime(!$photo ? 'now' : $photo->date);
        return $date->format($format);
    }

    public function as_array() {
        $result = parent::as_array();

        $result['thumbnailLink'] = function() {
            $photo = $this->getPhoto();
            return !$photo ? false : $photo->getThumbnailLink();
        };
        $result['date'] = function() { return $this->getFormattedDate(); };
        $result['formattedDate'] = function() { return $this->getFormattedDate(Setting::get('albumDateFormat')); };

        return $result;
    }

    public static function search($orm, $input) {
        $albumTable = DB_PREFIX . 'album';
        return $orm->where_any_is([
            [ "{$albumTable}.name" => "%{$input}%" ],
            [ "{$albumTable}.description" => "%{$input}%" ]
        ], 'LIKE');
    }

    public static function in_month($orm, $month) {
        $photoTable = DB_PREFIX . 'photo';

        try {
            $dateStart = new DateTime($month . '-01');
            $dateEnd = new DateTime($month . '-01');
            $dateEnd->add(new DateInterval('P1M'));

            return $orm->where_gte("{$photoTable}.date", $dateStart->format('Y-m-d H:i:s'))
                ->where_lt("{$photoTable}.date", $dateEnd->format('Y-m-d H:i:s'));
        } catch(Exception $ex) {
            // Return nothing
            return $orm->where_id_is(-1);
        }
    }

    public static function getAlbums($limit, array $filter = [], $page = 1) {
        $photoTable = DB_PREFIX . 'photo';
        $albumTable = DB_PREFIX . 'album';
        $table = DB_PREFIX . 'album_photo';

        $query = Album::select("{$albumTable}.*");
        if (isset($filter['search'])) $query = $query->filter('search', $filter['search']);
        if (isset($filter['month'])) $query = $query->filter('in_month', $filter['month']);

        $albums = $query->left_outer_join($table, array("{$table}.album_id", '=', "{$albumTable}.id"))
            ->left_outer_join($photoTable, array("{$photoTable}.id", '=', "{$table}.photo_id"))
            ->order_by_desc("{$albumTable}.id")
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
}