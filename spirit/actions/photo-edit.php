<?php

$ids = $params;

for ($i = 0; $i < count($ids); $i++) {
    $photo = Photo::find_one($_POST['id'][$i]);
    if (!$photo) continue;

    // Validate date
    try {
        $date = new DateTime($_POST['date'][$i]);
    } catch (Exception $ex) {
        $date = new DateTime();
    }

    // Save albums
    $albums = explode(',', $_POST['albums'][$i]);
    AlbumPhoto::where('photo_id', $photo->id)->delete_many();

    foreach ($albums as $id) {
        if (!Album::find_one($id)) continue;

        $item = AlbumPhoto::create();
        $item->set([ 'photo_id' => $photo->id, 'album_id' => $id ]);
        $item->save();
    }

    // Save record
    $photo->set([
        'title' => $_POST['title'][$i] == '' ? '(Untitled)' : $_POST['title'][$i],
        'date' => $date->format('Y-m-d H:i:s'),
        'description' => $_POST['description'][$i]
    ]);

    $photo->save();
}

Route::redirect('/spirit/photos');