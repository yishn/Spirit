<?php

class Photo extends Model {
    public function albums() {
        return $this->has_many_through('Album');
    }

    public function user() {
        return $this->belongs_to('User');
    }

    public function getPermalink() {
        return Route::link("/photo/{$this->id}");
    }

    public function getThumbnailLink() {
        return Route::link("/photo/{$this->id}/size/thumb");
    }

    public function getLargeImageLink() {
        return Route::link("/photo/{$this->id}/size/large");
    }

    public function getEditLink() {
        return Route::link("/spirit/edit/photo/{$this->id}");
    }

    public function getFormattedDescription() {
        $parsedown = new Parsedown();
        return $parsedown->text($this->description);
    }

    public function generateThumbnail($size) {
        $filename = $this->filename;
        $contentDir = Route::config('contentDir');
        $path = "{$contentDir}photos/" . $filename;

        Thumb::render($path, $size);
    }

    public function as_array() {
        $result = parent::as_array();

        $result['permalink'] = $this->getPermalink();
        $result['thumbnailLink'] = $this->getThumbnailLink();
        $result['largeImageLink'] = $this->getLargeImageLink();
        $result['formattedDescription'] = function() { return $this->getFormattedDescription(); };

        return $result;
    }

    public static function in_album($orm, $album) {
        $photoTable = DB_PREFIX . 'photo';
        $albumTable = DB_PREFIX . 'album';
        $table = DB_PREFIX . 'album_photo';

        return $orm->select("{$photoTable}.*")
            ->join($table, array("{$table}.photo_id", '=', "{$photoTable}.id"))
            ->join($albumTable, array("{$albumTable}.id", '=', "{$table}.album_id"));
    }

    public static function in_month($orm, $month) {
        try {
            $dateStart = new DateTime($month . '-01');
            $dateEnd = new DateTime($month . '-01');
            $dateEnd->add(new DateInterval('P1M'));

            return $orm->where_gte('date', $dateStart->format('Y-m-d H:i:s'))
                ->where_lt('date', $dateEnd->format('Y-m-d H:i:s'));
        } catch(Exception $ex) {
            // Return nothing
            return $orm->where_id_is(-1);
        }
    }

    public static function search($orm, $input) {
        return $orm->where_any_is([
            [ 'title' => "%{$input}%" ],
            [ 'description' => "%{$input}%" ]
        ], 'LIKE');
    }

    public static function getPhotos($limit, array $filter = [], $page = 1) {
        $query = Model::factory('Photo');

        // Filter
        if (isset($filter['album'])) $query = $query->filter('in_album', $filter['album']);
        if (isset($filter['month'])) $query = $query->filter('in_month', $filter['month']);
        if (isset($filter['search'])) $query = $query->filter('search', $filter['search']);

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

        return [
            'hasPhotos' => count($photos) != 0,
            'photos' => $photos,

            'hasFilters' => isset($filter['album']) || isset($filter['month']) || isset($filter['search']),
            'filterSearch' => !isset($fitler['search']) ? false : $filter['search'],
            'filterAlbum' => !isset($filter['album']) ? false : $filter['album']->as_array(),
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