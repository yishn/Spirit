<?php

class Photo extends Model {
    public function albums() {
        return $this->has_many_through('Album');
    }

    public function user() {
        return $this->belongs_to('User');
    }

    public function getPermalink() {
        return Dispatcher::link("/photo/{$this->id}");
    }

    public function getThumbnailLink() {
        return Dispatcher::link("/photo/{$this->id}/size/thumb");
    }

    public function getLargeImageLink() {
        return Dispatcher::link("/photo/{$this->id}/size/large");
    }

    public function getEditLink() {
        return Dispatcher::link("/spirit/edit/photo/{$this->id}");
    }

    public function getFormattedDescription() {
        $parsedown = new Parsedown();
        return $parsedown->text($this->description);
    }

    public function generateThumbnail($size, $trim) {
        $filename = $this->filename;
        $contentDir = Setting::where('key', 'contentDir')->find_one()->value;
        $path = Dispatcher::config('abspath') . "/{$contentDir}photos/" . $filename;

        Thumb::render($path, $size, $trim);
    }

    public function as_array() {
        $result = parent::as_array();

        $result['permalink'] = $this->getPermalink();
        $result['thumbnailLink'] = $this->getThumbnailLink();
        $result['largeImageLink'] = $this->getLargeImageLink();
        $result['editLink'] = $this->getEditLink();
        $result['description'] = $this->getFormattedDescription();

        return $result;
    }
}