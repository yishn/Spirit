<?php

function is_size($value) {
    $regex = "/^\d+x(\d+)?|(\d+)?x\d+|<\d+$/";
    return is_numeric($value) || preg_match($regex, $value);
}

function is_timezone($value) {
    return in_array($value, timezone_identifiers_list());
}

function is_theme($value) {
    return in_array($value, array_map(function($theme) {
        return $theme['id'];
    }, Spirit::getThemes()));
}

// Check authorization
if (!$this->user->root)
    Spirit::error(401);

// Check values
foreach (Setting::$standards as $key => $standard) {
    if ($key == 'originalPhotoDownload') {
        Setting::set($key, isset($_POST[$key]) ? 'true' : 'false');
        continue;
    }

    if (!isset($_POST[$key])) continue;
    $value = $_POST[$key];

    if ($value == '') $value = $standard;
    if (($key == 'largeImageSize' || $key == 'thumbSize') && !is_size($value)) continue;
    if (($key == 'photosPerPage' || $key == 'albumsPerPage') && !is_numeric($value)) continue;
    if ($key == 'timezone' && !is_timezone($value)) continue;
    if ($key == 'theme' && !is_theme($value)) continue;

    Setting::set($key, $value);
}

Spirit::redirect('/spirit/settings');