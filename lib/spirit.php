<?php

function spirit_route($method, $paths, $funcs) {
    if (!is_array($paths)) $paths = [$paths];

    $paths = array_map(function($x) {
        if ($x != '*') return trim(BASE_PATH, '/') . rtrim($x, '/');
        return $x;
    }, $paths);

    return route($method, $paths, $funcs);
}
