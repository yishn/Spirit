<?php

$photo = Photo::find_one($params['id']);
if (!$photo) {
    Route::error(404);
    exit();
}

// Validate date
try {
    $date = new DateTime($_POST['date']);
} catch (Exception $ex) {
    $date = new DateTime();
}

// Save albums
$albums = explode(',', $_POST['albums']);
AlbumPhoto::where('photo_id', $photo->id)->delete_many();

foreach ($albums as $id) {
    if (!Album::find_one($id)) continue;

    $item = AlbumPhoto::create();
    $item->set([ 'photo_id' => $photo->id, 'album_id' => $id ]);
    $item->save();
}

// Save record
$photo->set([
    'title' => $_POST['title'] == '' ? '(Untitled)' : $_POST['title'],
    'date' => $date->format('Y-m-d H:i:s'),
    'description' => $_POST['description']
]);

$photo->save();
Route::redirect('/spirit/photos');