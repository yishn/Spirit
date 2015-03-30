<?php

$user = User::find_one($params['id']);
if (!$user) $user = User::create();

// Check data
if ($_POST['name'] == '' || $_POST['email'] == '')
    Route::redirect('/spirit/users/' . $user->id);

// Check authorization
if (!$this->user->root && !$user->is_new() && $this->user->id != $user->id)
    Route::error(401);

$user->set([
    'name' => $_POST['name'],
    'email' => $_POST['email']
]);

if ($_POST['password'] != '') {
    if ($_POST['verifypassword'] != $_POST['password'])
        Route::redirect('/spirit/users/' . $user->id);

    $user->generateHash($_POST['password']);
}

$user->save();
Route::redirect('/spirit/users');