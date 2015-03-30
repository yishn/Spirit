<?php

class User extends Model {
    public function photos() {
        return $this->has_many('Photo');
    }

    public function generateHash($password) {
        $salt = '$2a$15$' . strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
        $this->hash = crypt($password, $salt);
    }

    public function compareHash($password) {
        $hash = crypt($password, $this->hash);
        return $this->hash === $hash;
    }

    public function getAvatarLink() {
        return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?d=retro';
    }

    public function as_array() {
        $result = parent::as_array();
        $result['root'] = $result['root'] == 1;
        $result['avatarLink'] = $this->getAvatarLink();
        
        return $result;
    }

    public static function create() {
        $user = parent::factory('User')->create();
        $user->set([
            'id' => 'new',
            'email' => '',
            'name' => '',
            'hash' => '',
            'root' => false
        ]);
        return $user;
    }

    public static function getUsers() {
        $users = User::order_by_desc('root')
            ->order_by_desc('name')
            ->find_many();

        $users = array_map(function($user) {
            return $user->as_array();
        }, $users);

        return [
            'users' => $users
        ];
    }
}