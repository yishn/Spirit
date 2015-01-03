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

    public function getFormattedDescription() {
        $parsedown = new Parsedown();
        return $parsedown->text($this->description);
    }

    public function as_array() {
        $result = parent::as_array();
        $result['permalink'] = $this->getPermalink();
        $result['thumbnailLink'] = $this->getThumbnailLink();
        $result['largeImageLink'] = $this->getLargeImageLink();
        $result['description'] = $this->getFormattedDescription();

        return $result;
    }
}