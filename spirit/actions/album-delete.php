<?php

$album = Route::verifyModel('Album', $params['id']);
$album->delete();
Route::redirect('/spirit/albums');