<?php

$user = User::where('email', $_POST['email'])->find_one();

if ($user === false || !$user->compareHash($_POST['password']))
    Route::error(401);

Route::session('user', $user->id);
Route::redirect('/spirit');