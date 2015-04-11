<?php

class Photo extends Model {
    public function albums() {
        return $this->has_many_through('Album');
    }

    public function user() {
        return $this->belongs_to('User');
    }

    public function getPermalink() {
        return Spirit::link("/photo/{$this->id}");
    }

    public function getDownloadLink() {
        return Spirit::link("/photo/{$this->id}/download");
    }

    public function getThumbnailLink() {
        return Spirit::link("/photo/{$this->id}/size/thumb");
    }

    public function getLargeImageLink() {
        return Spirit::link("/photo/{$this->id}/size/large");
    }

    public function getEditLink() {
        return Spirit::link("/spirit/edit/photo/{$this->id}");
    }

    public function getFormattedDescription() {
        $parsedown = new Parsedown();
        return $parsedown->text($this->description);
    }

    public function getFormattedDate($format = 'Y-m-d H:i') {
        $date = new DateTime($this->date);
        return $date->format($format);
    }

    public function getAdjacentPhotos(array $filter = []) {
        $older = self::getFilteredQuery($filter)
            ->where('date', $this->date)
            ->where_lt('id', $this->id)
            ->order_by_desc('id')
            ->find_one();
        if (!$older) $older = self::getFilteredQuery($filter)
            ->where_lt('date', $this->date)
            ->order_by_desc('date')
            ->order_by_desc('id')
            ->find_one();

        $newer = self::getFilteredQuery($filter)
            ->where('date', $this->date)
            ->where_gt('id', $this->id)
            ->order_by_asc('id')
            ->find_one();
        if (!$newer) $newer = self::getFilteredQuery($filter)
            ->where_gt('date', $this->date)
            ->order_by_asc('date')
            ->order_by_asc('id')
            ->find_one();

        return [$older, $newer];
    }

    public function download() {
        $path = DIR_CONTENT . $this->filename;

        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: attachment; filename=' . strtr($this->filename, ' ', '-'));

        readfile($path);
        exit();
    }

    public function generateThumbnail($size) {
        $path = DIR_CONTENT . $this->filename;

        Thumb::render($path, $size);
        exit();
    }

    public function delete() {
        $path = DIR_CONTENT . $this->filename;
        unlink($path);

        AlbumPhoto::where('photo_id', $this->id)->delete_many();
        parent::delete();
    }

    public function as_array($includeAlbums = false, $includeUser = false, $includeAdjacents = false, array $filter = []) {
        $result = parent::as_array();

        $result['albums'] = false;
        if ($includeAlbums) {
            $albums = $this->albums()->find_many();
            $result['albums'] = array_map(function($album) { return $album->as_array(); }, $albums);
        }

        $result['owner'] = false;
        if ($includeUser) {
            $user = $this->user()->find_one();
            $result['owner'] = !$user ? false : $user->as_array();
        }

        $result['olderPhoto'] = $result['newerPhoto'] = false;
        if ($includeAdjacents) {
            list($older, $newer) = $this->getAdjacentPhotos($filter);
            $result['olderPhoto'] = !$older ? false : $older->as_array();
            $result['newerPhoto'] = !$newer ? false : $newer->as_array();
        }

        $result = array_merge($result, [
            'hasAlbums' => count($result['albums']) != 0,
            'date' => $this->getFormattedDate(),
            'month' => $this->getFormattedDate('Y-m'),
            'formattedDate' => $this->getFormattedDate(Setting::get('dateFormat')),
            'permalink' => $this->getPermalink(),
            'downloadable' => Setting::get('originalPhotoDownload') == 'true',
            'downloadLink' => $this->getDownloadLink(),
            'thumbnailLink' => $this->getThumbnailLink(),
            'largeImageLink' => $this->getLargeImageLink(),
            'formattedDescription' => function() { return $this->getFormattedDescription(); }
        ]);

        return $result;
    }

    private static function getFilteredQuery(array $filter = []) {
        $query = Model::factory('Photo');

        // Filter
        if (isset($filter['album'])) $query = $query->filter('in_album', $filter['album']);
        if (isset($filter['month'])) $query = $query->filter('in_month', $filter['month']);
        if (isset($filter['search'])) $query = $query->filter('search', $filter['search']);

        return $query;
    }

    public static function getPhotos($limit, array $filter = [], $page = 1) {
        $query = self::getFilteredQuery($filter);

        // Sorting albums correctly
        if (isset($filter['album']) && $filter['album']->chronological == 1)
            $query = $query->order_by_asc('date')->order_by_asc('id');
        $query = $query->order_by_desc('date')->order_by_desc('id');

        // Limit correctly
        $photos = $query->limit($limit + 1)
            ->offset(($page - 1) * $limit)
            ->find_many();

        $hasPreviousPage = $page != 1 && count($photos) != 0;
        $hasNextPage = count($photos) == $limit + 1;
        if ($hasNextPage) array_pop($photos);
        
        $photos = array_map(function($photo) {
            return $photo->as_array();
        }, $photos);

        list($w, $h) = Thumb::getSize(Setting::get('thumbSize'));

        return [
            'hasPhotos' => count($photos) != 0,
            'photos' => $photos,

            'thumbWidth' => $w,
            'thumbHeight' => $h,

            'hasFilters' => isset($filter['album']) || isset($filter['month']) || isset($filter['search']),
            'filterSearch' => !isset($filter['search']) ? false : [ 'search' => $filter['search'] ],
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

    public static function in_album($orm, $album) {
        return $album->photos();
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

    public static function search($orm, $input) {
        $terms = array_filter(explode(' ', $input));

        foreach ($terms as $term) {
            $condition = [
                [ 'title' => "%{$term}%" ],
                [ 'description' => "%{$term}%" ]
            ];

            $orm = $orm->where_any_is($condition, 'LIKE');
        }

        return $orm;
    }
}