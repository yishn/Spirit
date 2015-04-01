<?php

$album = Spirit::verifyModel('Album', $params['id']);
$album->delete();
Spirit::redirect('/spirit/albums');