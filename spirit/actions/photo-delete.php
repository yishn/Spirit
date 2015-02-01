<?php

$ids = Route::verifyModels('Photo', $params['ids']);

foreach ($ids as $id) {
    Photo::find_one($id)->delete();
}

Route::redirect('/spirit/photos');