<?php

for ($i = 0; $i < count($_POST['id']); $i++) {
    $photo = Photo::find_one($_POST['id'][$i]);
    if (!$photo) continue;

    // Validate date
    try {
        $date = new DateTime($_POST['date'][$i]);
    } catch (Exception $ex) {
        $date = new DateTime();
    }

    // Save albums
    $albums = NULL;

    if (isset($_POST['albums']))
        $albums = explode(',', $_POST['albums'][$i]);
    if (isset($_POST['globalalbums']) && $_POST['globalalbums'] != '')
        $albums = explode(',', $_POST['globalalbums']);

    if (!is_null($albums)) {
        AlbumPhoto::where('photo_id', $photo->id)->delete_many();

        foreach ($albums as $id) {
            if (!Album::find_one($id)) continue;

            $item = AlbumPhoto::create();
            $item->set([ 'photo_id' => $photo->id, 'album_id' => $id ]);
            $item->save();
        }
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