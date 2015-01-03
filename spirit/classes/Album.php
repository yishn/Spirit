<?php

class Album extends Model {
    public function photos() {
        return $this->has_many_through('Photo');
    }
}