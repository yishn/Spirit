<?php

class User extends Model {
    public function photos() {
        return $this->has_many('Photo');
    }

    public function generateSalt() {
        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
        $salt = sprintf("$2a$%02d$", 15) . $salt;
        $this->salt = $salt;
    }

    public function getHash($password) {
        return crypt($password, $this->salt);
    }

    public function getAvatarLink() {
        return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?d=retro';
    }

    public function as_array() {
        $result = parent::as_array();
        $result['admin'] = $result['admin'] == 1;
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
            'salt' => '',
            'admin' => false
        ]);
        return $user;
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