<?php

$ids = explode(',', $params['ids']);
Spirit::verifyModels('Photo', $ids);

foreach ($ids as $id) {
    Photo::find_one($id)->delete();
}

Spirit::redirect('/spirit/photos');