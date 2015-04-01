<?php

$user = User::where('email', $_POST['email'])->find_one();

if($user === false) {
    $user = User::create();
    $user->generateHash('');
}

if (!$user->compareHash($_POST['password']) || $user->is_new())
    Spirit::redirect('/spirit/login/invalid');

Spirit::session('user', $user->id);
Spirit::redirect('/spirit');