<?php

$ids = explode(',', $params['ids']);
Spirit::verifyModels('Photo', $ids);

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
    $albums = array_map('intval', array_filter(explode(',', $_POST['albums'][$i])));

    if (isset($_POST['globalalbums']) && $_POST['globalalbums'] != '')
        $albums = array_merge($albums, explode(',', $_POST['globalalbums']));

    if (!is_null($albums)) {
        $oldalbums = AlbumPhoto::where('photo_id', $photo->id)->find_many();
        $oldalbums = array_map(function($albumphoto) {
            return $albumphoto->album_id;
        }, $oldalbums);

        foreach ($albums as $id) {
            if (in_array($id, $oldalbums)) continue;

            $item = AlbumPhoto::create();
            $item->set([ 'photo_id' => $photo->id, 'album_id' => $id ]);
            $item->save();
        }

        foreach ($oldalbums as $id) {
            if (in_array($id, $albums)) continue;

            AlbumPhoto::where('photo_id', $photo->id)->where('album_id', $id)->delete_many();
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

Spirit::redirect('/spirit/photos');