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
}