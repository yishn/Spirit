<?php

$user = Route::verifyModel('User', $params['id']);

// Check authorization
if ($user->root || !$this->user->root && $this->user->id != $user->id) {
    Route::error(401);
    die();
}

$user->delete();
Route::redirect('/spirit/users');