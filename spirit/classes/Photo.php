<?php

class Photo extends Model {
    public function albums() {
        return $this->has_many_through('Album');
    }

    public function user() {
        return $this->belongs_to('User');
    }
}