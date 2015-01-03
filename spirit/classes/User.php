<?php

class User extends Model {
    public function photos() {
        return $this->has_many('Photo');
    }
}