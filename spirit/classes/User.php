<?php

class User extends Model {
    public function photos() {
        return $this->has_many('Photo');
    }

    public function getHash() {
        return md5(strtolower(trim($this->email)));
    }

    public function getAvatarLink() {
        return 'http://www.gravatar.com/avatar/' + $this->getHash() + '?d=retro';
    }

    public function as_array() {
        $result = parent::as_array();
        $result['admin'] = $result['admin'] == 1;
        $result['avatarLink'] = $this->getAvatarLink();
        
        return $result;
    }

    public static function getUsers() {
        $users = User::order_by_desc('admin')
            ->order_by_desc('id')
            ->find_many();

        $users = array_map(function($user) {
            return $user->as_array();
        }, $users);

        return [
            'users' => $users
        ];
    }
}