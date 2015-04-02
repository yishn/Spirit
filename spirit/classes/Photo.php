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

    public function as_array($includeAlbums = true, $includeUser = true) {
        $result = parent::as_array();

        if ($includeAlbums) {
            $albums = $this->albums()->find_many();
            $result['albums'] = array_map(function($album) { return $album->as_array(); }, $albums);
        } else {
            $result['albums'] = false;
        }

        if ($includeUser) {
            $user = $this->user()->find_one();
            $result['owner'] = !$user ? false : $user->as_array();
        } else {
            $result['owner'] = false;
        }

        $result['hasAlbums'] = $result['albums'] !== false;
        $result['date'] = $this->getFormattedDate();
        $result['formattedDate'] = $this->getFormattedDate(Setting::get('dateFormat'));
        $result['permalink'] = $this->getPermalink();
        $result['downloadable'] = Setting::get('originalPhotoDownload') == 'true';
        $result['downloadLink'] = $this->getDownloadLink();
        $result['thumbnailLink'] = $this->getThumbnailLink();
        $result['largeImageLink'] = $this->getLargeImageLink();
        $result['formattedDescription'] = function() { return $this->getFormattedDescription(); };

        return $result;
    }

    public static function getPhotos($limit, array $filter = [], $page = 1) {
        $query = Model::factory('Photo');

        // Filter
        if (isset($filter['album'])) $query = $query->filter('in_album', $filter['album']);
        if (isset($filter['month'])) $query = $query->filter('in_month', $filter['month']);
        if (isset($filter['search'])) $query = $query->filter('search', $filter['search']);

        $photos = $query->order_by_desc('date')
            ->order_by_desc('id')
            ->limit($limit + 1)
            ->offset(($page - 1) * $limit)
            ->find_many();

        $hasPreviousPage = $page != 1 && count($photos) != 0;
        $hasNextPage = count($photos) == $limit + 1;
        if ($hasNextPage) array_pop($photos);
        
        $photos = array_map(function($photo) {
            return $photo->as_array(false, false);
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
        return $orm->where_any_is([
            [ 'title' => "%{$input}%" ],
            [ 'description' => "%{$input}%" ]
        ], 'LIKE');
    }
}