<?php

$user = Spirit::verifyModel('User', $params['id']);

// Check authorization
if ($user->root || !$this->user->root && $this->user->id != $user->id) {
    Spirit::error(401);
    die();
}

$user->delete();
Spirit::redirect('/spirit/users');