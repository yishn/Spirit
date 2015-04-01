<?php

$album = Album::find_one($params['id']);
if (!$album) $album = Album::create();

$album->set([
    'name' => $_POST['name'] == '' ? '(Untitled)' : $_POST['name'],
    'description' => $_POST['description'],
    'chronological' => $_POST['chronological']
]);

$album->save();
Spirit::redirect('/spirit/albums');