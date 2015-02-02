<?php

class User extends Model {
    public function photos() {
        return $this->has_many('Photo');
    }

    public static function getUsers() {
        $users = User::order_by_desc('admin')
            ->order_by_desc('id')
            ->find_many();

        $users = array_map(function($user) {
            return $user->as_array();
        }, $users);

        return $users;
    }
}