<?php

$user = User::find_one($params['id']);
if (!$user) $user = User::create();

// Check data
if ($_POST['name'] == '' || $_POST['email'] == '')
    Spirit::redirect('/spirit/users/' . $user->id);

// Check authorization
if (!$this->user->root && !$user->is_new() && $this->user->id != $user->id)
    Spirit::error(401);

$user->set([
    'name' => $_POST['name'],
    'email' => $_POST['email']
]);

if ($user->is_new() && $_POST['password'] == '')
    Spirit::redirect('/spirit/users/' . $user->id);

if ($_POST['password'] != '') {
    if ($_POST['verifypassword'] != $_POST['password'])
        Spirit::redirect('/spirit/users/' . $user->id);

    $user->generateHash($_POST['password']);
}

$user->save();
Spirit::redirect('/spirit/users');