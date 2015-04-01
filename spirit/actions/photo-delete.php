<?php

$ids = Spirit::verifyModels('Photo', $params['ids']);

foreach ($ids as $id) {
    Photo::find_one($id)->delete();
}

Spirit::redirect('/spirit/photos');