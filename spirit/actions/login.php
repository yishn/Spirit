<?php

$user = User::where('email', $_POST['email'])->find_one();

if($user === false) {
    $user = User::create();
    $user->generateHash('');
}

if (!$user->compareHash($_POST['password']) || $user->is_new())
    Route::redirect('/spirit/login/invalid');

Route::session('user', $user->id);
Route::redirect('/spirit');