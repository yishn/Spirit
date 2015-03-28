<?php

$user = User::find_one($params['id']);
if (!$user) $user = User::create();

if ($_POST['name'] == '' || $_POST['email'] == '')
    Route::redirect('/spirit/users/' . $user->id);

$user->set([
    'name' => $_POST['name'],
    'email' => $_POST['email']
]);

if ($_POST['password'] != '') {
    if ($_POST['verifypassword'] != $_POST['password'])
        Route::redirect('/spirit/users/' . $user->id);

    $user->generateHash($_POST['password']);
    echo $user->salt . '<br>';
    echo $user->hash;
}

$user->save();
Route::redirect('/spirit/users');