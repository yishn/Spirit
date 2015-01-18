<?php

function getExifDate($path) {
    try {
        $exif = @exif_read_data($path);

        if (isset($exif['DateTimeOriginal'])) 
            return new DateTime($exif['DateTimeOriginal']);
    } catch (Exception $ex) { }
    
    return new DateTime('now');
}

$uploaddir = Route::config('contentDir') . 'photos/';
$ids = [];

if (!is_dir($uploaddir)) {
    mkdir($uploaddir);
}

for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
    $title = basename($_FILES['file']['name'][$i]);
    $filename = uniqid() . '-' . $title;
    $path = $uploaddir . $filename;

    if (substr($_FILES['file']['type'][$i], 0, 6) != 'image/') continue;
    if (!move_uploaded_file($_FILES['file']['tmp_name'][$i], $path)) continue;

    $title = substr($title, 0, strpos($title, '.'));
    $date = getExifDate($path);

    $photo = Photo::create();
    $photo->set([
        'user_id' => $this->user->id,
        'filename' => $filename,
        'title' => $title,
        'date' => $date->format('Y-m-d H:i')
    ]);
    $photo->save();

    $ids[] = $photo->id;
}

if (count($ids) > 0)
    Route::redirect('spirit/photos/edit/' . implode(',', $ids));
else
    Route::redirect('spirit/photos');